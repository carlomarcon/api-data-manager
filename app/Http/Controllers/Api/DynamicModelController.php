<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DynamicModelController extends Controller
{
  /**
   *
   *
   * @param string $tableNameAlias L'alias della tabella dalla richiesta (es. 'prodotti').
   * @return Model|null
   */
  private function resolveModel(string $tableNameAlias): ?Model
  {
    // Cerca la classe nel file in config tra quelle mappate
    $modelClass = config("dynamic_models.mapping." . $tableNameAlias);

    // Istanzio la classe
    if ($modelClass && class_exists($modelClass)) {
      return new $modelClass();
    }

    return null;
  }

  public function update(Request $request)
  {
    // Validazione del payload di base tramite validator
    $validator = Validator::make($request->all(), [
      "tab" => "required|string",
      "chiave" => "required|array|min:1",
      "campo" => "required|string",
      "valore" => "present",
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          "message" => "Payload non valido.",
          "errors" => $validator->errors(),
        ],
        400
      );
    }

    // Risoluzione dinamica del modello
    $model = $this->resolveModel($request->input("tab"));

    if (!$model) {
      return response()->json(
        ["message" => "Tabella '{$request->input("tab")}' non supportata."],
        404
      );
    }

    $chiave = $request->input("chiave");
    $record = $model->where($chiave)->first();

    // Se il record non viene trovato, restituiscr un errore
    if (!$record) {
      return response()->json(
        ["message" => "Record non trovato con la chiave specificata."],
        404
      );
    }

    // Aggiornamento del campo
    $campo = $request->input("campo");
    $valore = $request->input("valore");

    if (!in_array($campo, $record->getFillable())) {
      return response()->json(
        ["message" => "Il campo '{$campo}' non può essere aggiornato."],
        403
      );
    }

    $record->{$campo} = $valore;
    $record->save();

    // Risposta di successo
    return response()->json(["message" => "Record aggiornato con successo."]);
  }

  public function updateMultiple(Request $request)
  {
    $validator = Validator::make($request->all(), [
      "tab" => "required|string",
      "chiave" => "required|array|min:1",
      "dati" => "required|array|min:1",
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          "message" => "Payload non valido.",
          "errors" => $validator->errors(),
        ],
        400
      );
    }

    // Risoluzione del modello (identica a prima)
    $model = $this->resolveModel($request->input("tab"));
    if (!$model) {
      return response()->json(
        ["message" => "Tabella '{$request->input("tab")}' non supportata."],
        404
      );
    }

    // Ricerca del record (identica a prima)
    $chiave = $request->input("chiave");
    $record = $model->where($chiave)->first();
    if (!$record) {
      return response()->json(
        ["message" => "Record non trovato con la chiave specificata."],
        404
      );
    }

    // Aggiornamento in blocco
    $dataToUpdate = $request->input("dati");

    $record->update($dataToUpdate);

    return response()->json([
      "message" => "Record aggiornato con successo (multi-campo).",
      "record" => $record, // Restituiamo il record aggiornato per conferma
    ]);
  }

  public function insert(Request $request)
  {
    // Validazione del payload di base
    $validator = Validator::make($request->all(), [
      "tab" => "required|string",
      "dati" => "required|array|min:1",
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          "message" => "Payload non valido.",
          "errors" => $validator->errors(),
        ],
        400
      );
    }

    $model = $this->resolveModel($request->input("tab"));

    if (!$model) {
      return response()->json(
        ["message" => "Tabella '{$request->input("tab")}' non supportata."],
        404
      );
    }

    $dataToInsert = $request->input("dati");

    // inserimento dei dati
    try {
      // Cerco di dare atomicità al fine di evitare di il rischio di inserimento parziale dei dati
      DB::transaction(function () use ($model, $dataToInsert) {
        $now = now();
        foreach ($dataToInsert as &$row) {
          $row["created_at"] = $now;
          $row["updated_at"] = $now;
        }

        $model->insert($dataToInsert);

        // Alternativa, un pò più lenta
        // foreach ($dataToInsert as $recordData) {
        //     $model->create($recordData);
        // }
      });
    } catch (\Exception $e) {
      // Se dovessero presentarsi dei problemi
      return response()->json(
        [
          "message" => 'Errore durante l\'inserimento dei dati.',
          "error" => $e->getMessage(),
        ],
        500
      );
    }

    return response()->json(
      [
        "message" => "Inserimento completato con successo.",
      ],
      201
    );
  }
}

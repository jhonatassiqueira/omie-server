<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OmieController extends Controller
{

    public function ListAccountsReceivable(Request $request){
        $page = 1;
        $curl = curl_init();
        $result = array();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.omie.com.br/api/v1/financas/contareceber/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
              "app_key": "'.$request->key .'",
              "app_secret": "'.$request->secret .'",
              "call": "ListarContasReceber",
              "param": [
                  {
                      "pagina": '.$page.',
                      "registros_por_pagina": 500,
                      "apenas_importado_api": "N"
                  }
              ]
          }',
            CURLOPT_HTTPHEADER => array(
              ': ',
              'Content-Type: application/json'
            ),
          ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $dates = json_decode($response)->conta_receber_cadastro;
        
        //===================== DESCOBRINDO O TOTAL DE PÁGINAS ==============================
        $AllPages = json_decode($response)->total_de_paginas;

        array_push($result, $dates);

        //===================== LOOP SOBRE TODAS AS PÁGINAS ==============================
        if($page < $AllPages){
            for ($page=2; $page <= $AllPages; $page++) { 
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://app.omie.com.br/api/v1/financas/contareceber/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "app_key": "'.$request->key .'",
                        "app_secret": "'.$request->secret .'",
                        "call": "ListarContasReceber",
                        "param": [
                            {
                                "pagina": '.$page.',
                                "registros_por_pagina": 500,
                                "apenas_importado_api": "N"
                            }
                        ]
                    }',
                    CURLOPT_HTTPHEADER => array(
                        ': ',
                        'Content-Type: application/json'
                    ),
                    ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $dates = json_decode($response)->conta_receber_cadastro;
                array_push($result, $dates);
            }
        }

        return $result;
    }
}

<?php
namespace App\Controllers;

use App\Utils\View;
use App\Utils\HttpResponse;
use App\Utils\HtmlResponse;

class HomeController {
    public function index(): HtmlResponse {
        $htmlContent = View::render('pages/home', [
            'title' => 'AntiGolpe - Proteção contra golpes',
            'hero' => [
                'title' => 'Pare de cair em golpes',
                'subtitle' => 'Verifique anúncios em segundos',
                'image' => View::asset('images/hero-mockup.png') 
            ],
            'features' => [
                [
                    'icon' => 'icons/analysis.svg',
                    'title' => 'Análise em tempo real',
                    'animation' => 'left'
                ]
            ],
            'faq' => [
                'title' => 'Perguntas Frequentes',
                'items' => [
                    [
                        'question' => 'Como o bot identifica se algo é golpe?',
                        'answer' => [
                            'Análise de padrões suspeitos em mensagens',
                            'Verificação de links maliciosos',
                            'Consulta em bancos de dados de fraudes'
                        ],
                        'is_open' => true
                    ],
                    [
                        'question' => 'Posso usar para checar links do WhatsApp?',
                        'answer' => 'Sim, nosso sistema analisa links do WhatsApp, Instagram e outras plataformas.',
                        'is_open' => false
                    ],
                    [
                        'question' => 'Meus dados estão seguros?',
                        'answer' => [
                            'Não armazenamos conteúdo das mensagens',
                            'Criptografia de ponta a ponta',
                            'Conformidade com LGPD'
                        ],
                        'is_open' => false
                    ]
                ]
            ]
        ]);

        return new HtmlResponse(200, $htmlContent);
    }
}
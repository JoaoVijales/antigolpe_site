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
                'image' => 'images/hero-mockup.png'
            ],
            'features' => [
                [
                    'icon' => 'icons/anlize-icon0.svg',
                    'title' => 'Análise em tempo real',
                    'animation' => 'left'
                ],
                [
                    'icon' => 'icons/play-icon0.svg',
                    'title' => 'Cobertura ampla de anúncios',
                    'animation' => 'right'
                ],
                [
                    'icon' => 'icons/note-icon0.svg',
                    'title' => 'Relatório detalhado do risco',
                    'animation' => 'left'
                ],
                [
                    'icon' => 'icons/menssage-icon0.svg',
                    'title' => 'Integração simples via WhatsApp',
                    'animation' => 'right'
                ],
                [
                    'icon' => 'icons/database-icon0.svg',
                    'title' => 'Histórico de verificações',
                    'animation' => 'left'
                ]
            ],

            'plans' => [
                [
                    'id' => 'basic',
                    'name' => 'Básico',
                    'price' => '47,90',
                    'benefits' => [
                        ['text' => '5 análises por mês', 'highlight' => false],
                        ['text' => 'Suporte por email', 'highlight' => false]
                    ],
                    'button_text' => 'Selecionar Plano',
                    'highlight' => false
                ],
                [
                    'id' => 'pro',
                    'name' => 'Profissional',
                    'price' => '157,90',
                    'benefits' => [
                        ['text' => 'Análises ilimitadas', 'highlight' => true],
                        ['text' => 'Suporte prioritário'],
                        ['text' => 'Acesso antecipado'],
                        ['text' => 'Até 3 contas']
                    ],
                    'button_text' => 'Selecionar Plano',
                    'highlight' => true
                ],
                [
                    'id' => 'enterprise',
                    'name' => 'Empresarial',
                    'price' => 'A partir de 197,90',
                    'benefits' => [
                        ['text' => 'Tudo do plano Pro'],
                        ['text' => 'Personalização'],
                        ['text' => 'Múltiplos acessos', 'highlight' => false],
                        ['text' => 'Suporte dedicado', 'highlight' => false]
                    ],
                    'button_text' => 'Falar com Vendas',
                    'highlight' => false
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
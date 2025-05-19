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
                    'animation' => 'left',
                    'img_class' => 'anlize-icon',
                    'title_class' => 'text2',
                ],
                [
                    'icon' => 'icons/play-icon0.svg',
                    'title' => 'Cobertura ampla de anúncios',
                    'animation' => 'right',
                    'img_class' => 'play-icon',
                    'title_class' => 'text2',
                ],
                [
                    'icon' => 'icons/note-icon0.svg',
                    'title' => 'Relatório detalhado do risco',
                    'animation' => 'left',
                    'img_class' => 'note-icon',
                    'title_class' => 'text2',
                ],
                [
                    'icon' => 'icons/menssage-icon0.svg',
                    'title' => 'Integração simples via WhatsApp',
                    'animation' => 'right',
                    'img_class' => 'menssage-icon',
                    'title_class' => 'text3',
                ],
                [
                    'icon' => 'icons/database-icon0.svg',
                    'title' => 'Histórico de verificações',
                    'animation' => 'left',
                    'img_class' => 'database-icon',
                    'title_class' => 'text2',
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
                        ['text' => 'Análises ilimitadas', 'highlight' => false],
                        ['text' => 'Suporte prioritário', 'highlight' => false],
                        ['text' => 'Acesso antecipado', 'highlight' => false],
                        ['text' => 'Até 3 contas', 'highlight' => false]
                    ],
                    'button_text' => 'Selecionar Plano',
                    'highlight' => true
                ],
                [
                    'id' => 'enterprise',
                    'name' => 'Empresarial',
                    'price' => 'A partir de 197,90',
                    'benefits' => [
                        ['text' => 'Tudo do Plano Profissional', 'highlight' => false],
                        ['text' => 'Análise por demanda', 'highlight' => false],
                        ['text' => 'Personalize conforme sua necessidade', 'highlight' => true],
                        ['text' => 'Múltiplos acessos para sua equipe', 'highlight' => true]
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
                        'response' => 'Nosso agente faz uma busca rápida na internet para verificar se o link, produto ou loja tem sinais de golpe. Ele analisa:',
                        'lsit' => [
                            'Se o site é oficial ou usa um domínio estranho',
                            'Se a loja tem reclamações em sites como o Reclame Aqui, Procon ou fóruns de denúncias',
                            'Se há promessas exageradas ou condições fora da realidade'
                        ],
                        'is_open' => true
                    ],
                    [
                        'question' => 'Posso usar o bot para checar links do WhatsApp ou Instagram?',
                        'response' => 'Sim! Nosso bot é capaz de analisar links de diversas plataformas, incluindo:',
                        'list' => [
                            'Links do WhatsApp',
                            'Links do Instagram',
                            'Links do Facebook',
                            'Links do Twitter',
                            'Links de sites de e-commerce',
                            'Links de anúncios em marketplaces',
                            'Links de mensagens suspeitas'
                        ],
                        'is_open' => false
                    ],
                    [
                        'question' => 'o bot pode ser usado para checar mensagens de texto?',
                        'response' => 'Sim! Além de links, nosso bot pode analisar o conteúdo de mensagens de texto para identificar:',
                        'list' => [
                            'Padrões comuns de golpes',
                            'Promessas suspeitas',
                            'Pedidos de informações pessoais',
                            'Urgência indevida',
                            'Ofertas muito boas para serem verdade'
                        ],
                        'is_open' => false
                    ],
                    [
                        'question' => 'Meus dados estão seguros?',
                        'response' => 'Absolutamente! Segurança é nossa prioridade:',
                        'answer' => [
                            'Não armazenamos conteúdo das mensagens',
                            'As análises são feitas em tempo real',
                            'Criptografia de ponta a ponta',
                            'Conformidade com LGPD',
                        ],
                        'is_open' => false
                    ]
                ]
            ]
        ]);

        return new HtmlResponse(200, $htmlContent);
    }
}
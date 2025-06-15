<?php
namespace App\Controllers;

use App\Utils\View;
use App\Utils\HttpResponse;
use App\Utils\HtmlResponse;

class HomeController {
    public function index(): HtmlResponse {
        $htmlContent = View::render('pages/home', [
            'title' => 'É Cilada Bot - Proteção contra golpes',
            'hero' => [
                'title' => 'Pare de cair em golpes: verifique anúncios em segundos',
                'subtitle' => 'Envie o link ou screenshot do anúncio e receba um veredicto de segurança via WhatsApp em minutos.',
                'image' => 'images/hero-mockup.png'
            ],
            'features' => [
                [
                    'icon' => '<svg class="icon_color" width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M30.0917 17.1427C30.0917 25.3142 23.4673 31.9385 15.2958 31.9385C7.1243 31.9385 0.5 25.3142 0.5 17.1427C0.5 8.97116 7.1243 2.34686 15.2958 2.34686" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M0.5 17.1427H17.5721" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M15.2959 2.34686C11.8777 6.55035 9.88474 11.732 9.60522 17.1427C9.88474 22.5533 11.8777 27.735 15.2959 31.9385C17.9871 28.6292 19.7948 24.7135 20.5784 20.557" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M17.4969 8.5295C16.6316 8.37896 16.6316 7.13665 17.4969 6.98611C20.6321 6.44068 23.1256 4.05274 23.8061 0.944128L23.8583 0.705844C24.0454 -0.149434 25.2634 -0.154755 25.4579 0.698853L25.5213 0.976543C26.2271 4.07047 28.7211 6.43827 31.8477 6.98219C32.7175 7.1335 32.7175 8.38211 31.8477 8.53345C28.7211 9.07734 26.2271 11.4451 25.5213 14.5391L25.4579 14.8168C25.2634 15.6704 24.0454 15.665 23.8583 14.8098L23.8061 14.5715C23.1256 11.4629 20.6321 9.07493 17.4969 8.5295Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
',
                    'title' => 'Análise em tempo real',
                    'animation' => 'left',
                    'img_class' => 'anlize-icon',
                    'title_class' => 'text2',
                ],
                [
                    'icon' => '<svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M30.0385 0H2.96154C1.60207 0 0.5 1.10207 0.5 2.46154V29.5385C0.5 30.898 1.60207 32 2.96154 32H30.0385C31.398 32 32.5 30.898 32.5 29.5385V2.46154C32.5 1.10207 31.398 0 30.0385 0Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12.1429 21.4894V10.8555C12.1439 10.6776 12.1918 10.5031 12.2819 10.3497C12.372 10.1963 12.5011 10.0694 12.656 9.98193C12.811 9.89447 12.9863 9.84952 13.1642 9.85164C13.3421 9.85376 13.5163 9.90287 13.6691 9.99399L22.7768 15.2863C22.9301 15.3749 23.0575 15.5023 23.146 15.6557C23.2345 15.8091 23.2811 15.9831 23.2811 16.1601C23.2811 16.3373 23.2345 16.5112 23.146 16.6646C23.0575 16.818 22.9301 16.9454 22.7768 17.034L13.6691 22.3509C13.5163 22.4421 13.3421 22.4912 13.1642 22.4933C12.9863 22.4954 12.811 22.4505 12.656 22.363C12.5011 22.2755 12.372 22.1486 12.2819 21.9952C12.1918 21.8418 12.1439 21.6673 12.1429 21.4894Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
',
                    'title' => 'Cobertura ampla de anúncios',
                    'animation' => 'right',
                    'img_class' => 'play-icon',
                    'title_class' => 'text2',
                ],
                [
                    'icon' => '<svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M30.0385 0H2.96154C1.60207 0 0.5 1.10207 0.5 2.46154V29.5385C0.5 30.898 1.60207 32 2.96154 32H30.0385C31.398 32 32.5 30.898 32.5 29.5385V2.46154C32.5 1.10207 31.398 0 30.0385 0Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12.1429 21.4894V10.8555C12.1439 10.6776 12.1918 10.5031 12.2819 10.3497C12.372 10.1963 12.5011 10.0694 12.656 9.98193C12.811 9.89447 12.9863 9.84952 13.1642 9.85164C13.3421 9.85376 13.5163 9.90287 13.6691 9.99399L22.7768 15.2863C22.9301 15.3749 23.0575 15.5023 23.146 15.6557C23.2345 15.8091 23.2811 15.9831 23.2811 16.1601C23.2811 16.3373 23.2345 16.5112 23.146 16.6646C23.0575 16.818 22.9301 16.9454 22.7768 17.034L13.6691 22.3509C13.5163 22.4421 13.3421 22.4912 13.1642 22.4933C12.9863 22.4954 12.811 22.4505 12.656 22.363C12.5011 22.2755 12.372 22.1486 12.2819 21.9952C12.1918 21.8418 12.1439 21.6673 12.1429 21.4894Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
',
                    'title' => 'Relatório detalhado do risco',
                    'animation' => 'left',
                    'img_class' => 'note-icon',
                    'title_class' => 'text2',
                ],
                [
                    'icon' => '<svg class="menssage-svg" width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M14.0385 18.9615L0.5 12.8077L32.5 0.5L20.1923 32.5L14.0385 18.9615Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14.0385 18.9615L21.4231 11.5769" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
',
                    'title' => 'Integração simples via Telegram',
                    'animation' => 'right',
                    'img_class' => 'menssage-icon',
                    'title_class' => 'text3',
                ],
                [
                    'icon' => '<svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.5 12.3077C25.3367 12.3077 32.5 9.55252 32.5 6.15385C32.5 2.75518 25.3367 0 16.5 0C7.66345 0 0.5 2.75518 0.5 6.15385C0.5 9.55252 7.66345 12.3077 16.5 12.3077Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M0.5 6.15384V25.8461C0.5 29.2431 7.66308 32 16.5 32C25.3369 32 32.5 29.2431 32.5 25.8461V6.15384" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M32.5 16C32.5 19.3969 25.3369 22.1538 16.5 22.1538C7.66308 22.1538 0.5 19.3969 0.5 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>',
                    'title' => 'Histórico de verificações',
                    'animation' => 'left',
                    'img_class' => 'database-icon',
                    'title_class' => 'text2',
                ]
            ],

            'plans' => [
                [
                    'id' => 'basic',
                    'plan_id' => 'planbasic',
                    'name' => 'Básico',
                    'price' => '17',
                    'benefits' => [
                        ['text' => '5 análises por mês', 'highlight' => false],
                        ['text' => 'Suporte por email', 'highlight' => false]
                    ],
                    'button_text' => 'Selecionar Plano',
                    'highlight' => false
                ],
                [
                    'id' => 'pro',
                    'plan_id' => 'planpro',
                    'name' => 'Profissional',
                    'price' => '97',
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
                    'id' => 'empr',
                    'name' => 'Empresarial',
                    'subtitle' => 'A partir de:',
                    'text_variation' => '2',
                    'price' => '57',
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
<?php
namespace App\Controllers;

use App\Utils\View;
use App\Utils\HttpResponse;
use App\Utils\HtmlResponse;

class DashboardController {
    public function index(): HtmlResponse {
        $htmlContent = View::render('pages/dashboard/plans', [
            'title' => 'AntiGolpe - Proteção contra golpes',
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
            ]
        ]);

        return new HtmlResponse(200, $htmlContent);
    }

    public function config(): HtmlResponse {
        $htmlContent = View::render('pages/dashboard/config', [
            'title' => 'AntiGolpe - Configurações',
            
        ]);

        return new HtmlResponse(200, $htmlContent);
    }

    public function register_phone(): HtmlResponse {
        $htmlContent = View::render('pages/dashboard/register_phone', [
            'title' => 'AntiGolpe - Cadastro de Whatsapp',
            'form_data' => [
                'id' => 'form-basic',
                'id_button' => 'update_phone',
                'id_input' => 'phone',
                'title' => 'Cadastre seu Whatsapp para continuar',
                'description' => 'Para continuar, cadastre seu numero Whatsapp, você recebera um código de verificação por sms.',
                'form_action' => '',
                'phone_input_placeholder' => 'Digite seu Whatsapp'
            ],
        ]);
        return new HtmlResponse(200, $htmlContent);
    }

    public function verify_phone(): HtmlResponse {
        $htmlContent = View::render('pages/dashboard/verify_phone', [
            'title' => 'AntiGolpe - Verificação de Whatsapp',
            'verify_whatsapp_data' => [
                'id' => 'form-verify-whatsapp',
                'id_button' => 'verify_whatsapp',
                'id_input' => 'code',
                'title' => 'Verifique seu Whatsapp',
                'description' => 'Para continuar, verifique seu numero Whatsapp, você recebera um código de verificação por sms.',
                'form_action' => '',
                'phone_input_placeholder' => 'Digite seu código de verificação'
            ],
        ]);
        return new HtmlResponse(200, $htmlContent);
    }
    
}
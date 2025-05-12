<?php

namespace App\Services;

use App\Services\Logger;

class GoogleAnalytics {
    private $config;
    private $logger;
    private $measurementId;
    private $apiSecret;
    private $clientId;
    private $propertyId;
    private $debugMode;

    public function __construct(Logger $logger) {
        $this->logger = $logger;
        $this->config = require ROOT_PATH . '/app/config/analytics.php';
        
        $this->measurementId = $this->config['ga_measurement_id'];
        $this->apiSecret = $this->config['ga_api_secret'];
        $this->clientId = $this->config['ga_client_id'];
        $this->propertyId = $this->config['ga_property_id'];
        $this->debugMode = $this->config['ga_debug_mode'];
    }

    public function trackPageView($pagePath, $pageTitle = '') {
        if (!$this->config['ga_track_events']['page_view']) {
            return;
        }

        $data = [
            'client_id' => $this->clientId,
            'events' => [
                [
                    'name' => 'page_view',
                    'params' => [
                        'page_location' => $this->getFullUrl($pagePath),
                        'page_title' => $pageTitle ?: $this->getPageTitle($pagePath),
                        'page_referrer' => $_SERVER['HTTP_REFERER'] ?? '',
                        'engagement_time_msec' => 100
                    ]
                ]
            ]
        ];

        $this->sendToGA($data);
    }

    public function trackButtonClick($buttonId, $buttonText, $pagePath) {
        if (!$this->config['ga_track_events']['button_click']) {
            return;
        }

        $data = [
            'client_id' => $this->clientId,
            'events' => [
                [
                    'name' => 'button_click',
                    'params' => [
                        'button_id' => $buttonId,
                        'button_text' => $buttonText,
                        'page_location' => $this->getFullUrl($pagePath),
                        'page_title' => $this->getPageTitle($pagePath)
                    ]
                ]
            ]
        ];

        $this->sendToGA($data);
    }

    public function trackFormSubmit($formId, $formName, $pagePath) {
        if (!$this->config['ga_track_events']['form_submit']) {
            return;
        }

        $data = [
            'client_id' => $this->clientId,
            'events' => [
                [
                    'name' => 'form_submit',
                    'params' => [
                        'form_id' => $formId,
                        'form_name' => $formName,
                        'page_location' => $this->getFullUrl($pagePath),
                        'page_title' => $this->getPageTitle($pagePath)
                    ]
                ]
            ]
        ];

        $this->sendToGA($data);
    }

    public function trackScrollDepth($depth, $pagePath) {
        if (!$this->config['ga_track_events']['scroll_depth']) {
            return;
        }

        $data = [
            'client_id' => $this->clientId,
            'events' => [
                [
                    'name' => 'scroll_depth',
                    'params' => [
                        'depth' => $depth,
                        'page_location' => $this->getFullUrl($pagePath),
                        'page_title' => $this->getPageTitle($pagePath)
                    ]
                ]
            ]
        ];

        $this->sendToGA($data);
    }

    private function sendToGA($data) {
        $url = "https://www.google-analytics.com/mp/collect?measurement_id={$this->measurementId}&api_secret={$this->apiSecret}";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($this->debugMode) {
            $this->logger->info("GA Request: " . json_encode($data));
            $this->logger->info("GA Response: " . $response);
        }

        if ($httpCode !== 204) {
            $this->logger->error("Erro ao enviar dados para GA: " . $response);
        }
    }

    private function getFullUrl($path) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        return "{$protocol}://{$host}{$path}";
    }

    private function getPageTitle($path) {
        // Mapeia caminhos para títulos
        $titles = [
            '/' => 'Página Inicial',
            '/dashboard' => 'Dashboard de Segurança',
            '/checkout' => 'Checkout',
            // Adicione mais mapeamentos conforme necessário
        ];

        return $titles[$path] ?? 'Página Desconhecida';
    }

    public function getTrackingCode() {
        return "
        <!-- Google Analytics 4 -->
        <script async src='https://www.googletagmanager.com/gtag/js?id={$this->measurementId}'></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{$this->measurementId}', {
                'client_id': '{$this->clientId}',
                'debug_mode': " . ($this->debugMode ? 'true' : 'false') . "
            });

            // Rastreamento de cliques em botões
            document.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A') {
                    gtag('event', 'button_click', {
                        'button_id': e.target.id,
                        'button_text': e.target.textContent.trim(),
                        'page_location': window.location.href,
                        'page_title': document.title
                    });
                }
            });

            // Rastreamento de envio de formulários
            document.addEventListener('submit', function(e) {
                if (e.target.tagName === 'FORM') {
                    gtag('event', 'form_submit', {
                        'form_id': e.target.id,
                        'form_name': e.target.name,
                        'page_location': window.location.href,
                        'page_title': document.title
                    });
                }
            });

            // Rastreamento de profundidade de rolagem
            let scrollDepths = [25, 50, 75, 100];
            let trackedDepths = new Set();
            
            window.addEventListener('scroll', function() {
                let scrollPercent = (window.scrollY + window.innerHeight) / document.documentElement.scrollHeight * 100;
                
                scrollDepths.forEach(depth => {
                    if (scrollPercent >= depth && !trackedDepths.has(depth)) {
                        trackedDepths.add(depth);
                        gtag('event', 'scroll_depth', {
                            'depth': depth,
                            'page_location': window.location.href,
                            'page_title': document.title
                        });
                    }
                });
            });
        </script>
        ";
    }
} 
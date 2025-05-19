// Gerencia a interatividade dos elementos FAQ
export class FaqHandler {
    static init() {
        this.setupToggleInteraction();
    }

    // Lógica para girar a seta ao abrir/fechar os detalhes do FAQ (copiada do index.php)
    static setupToggleInteraction() {
        document.querySelectorAll('details').forEach(detail => {
            detail.addEventListener('toggle', (e) => {
                const arrow = e.target.querySelector('img'); // Assumindo que a imagem da seta é a primeira <img> dentro de <details>
                if (arrow) { // Verifica se a seta foi encontrada
                    if (e.target.open) {
                        arrow.style.transform = 'rotate(180deg)';
                    } else {
                        arrow.style.transform = 'rotate(0deg)';
                    }
                }
            });
        });
    }
} 
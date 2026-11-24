# ver-video: ver e ouvir um vídeo

Corta o vídeo em cenas, salva um quadro de cada cena e folhas com 2 quadros por segundo. Desenha o mapa do som (fala, batidas, silêncio) e transcreve a fala com o tempo de cada trecho. Serve para anúncios da pesquisa, para referências que o Kenui manda e para conferir os nossos vídeos gerados.

## Instalar (uma vez por máquina)
    pip install "scenedetect[opencv-headless]" faster-whisper yt-dlp
O ffmpeg já vem na máquina. Na primeira transcrição, o faster-whisper baixa o modelo (padrão "small", ~500 MB) de huggingface.co.

## Usar
    python3 ferramentas/ver-video/ver_video.py VIDEO.mp4 --saida PASTA
    python3 ferramentas/ver-video/ver_video.py https://link-do-video --saida PASTA   # baixa com yt-dlp, se a rede deixar
Opções: `--modelo small|medium|large-v3` (mais lento, mais preciso) e `--idioma en`.
Depois, o agente abre `relatorio.md`, as imagens `cenas/` e `folha_N.jpg` e o `espectro.png`, e preenche as colunas "o que acontece", "estado do produto" e "física", além da seção "Leitura".

## Ferramentas abertas usadas
- ffmpeg: quadros, áudio e espectrograma
- PySceneDetect (github.com/Breakthrough/PySceneDetect): detecção de cortes
- faster-whisper (github.com/SYSTRAN/faster-whisper): transcrição local, sem custo
- yt-dlp (github.com/yt-dlp/yt-dlp): baixar por link

## Rede
- A transcrição precisa de huggingface.co. Os vídeos do Higgsfield vêm de d8j0ntlcm91z4.cloudfront.net e d2ol7oe51mr4n9.cloudfront.net.
- Esses endereços estão liberados no ambiente "operacao-drop (vídeo)", criado em 24/11/2026. Para usá-lo, selecione esse ambiente em Project settings, Cloud environment. No ambiente padrão (Built-in), cenas e som funcionam, mas a transcrição fica marcada como pendente.
- Facebook, TikTok e YouTube continuam bloqueados para download direto. Para usar um anúncio da Biblioteca do Meta, baixe o vídeo pelo navegador (menu do anúncio) e mande o arquivo no chat.

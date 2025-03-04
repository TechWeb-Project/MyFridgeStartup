
<div id="prova" class="prova">
    <button id="flip" class="flip-button btn btn-primary">
        <span class="front-text">Visualizza Dettagli</span>
        <span class="back-text" style="display: none;">Aggiungi Prodotto</span>
    </button>
</div>

<style>
    .prova {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 0;
        font-size: 1.5rem;
        font-weight: bold;
        transition: all 0.5s;
    }

    .flip-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 10px 20px;
        border-radius: 5px;
        transition: all 0.5s;
    }

</style>

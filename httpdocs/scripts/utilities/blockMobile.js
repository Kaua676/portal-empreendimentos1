(function () {
  const isMobile = window.innerWidth <= 768;

  if (isMobile) {
    document.write(`
      <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
          background: #f8f8f8;
          font-family: sans-serif;
          color: #333;
          display: flex;
          align-items: center;
          justify-content: center;
          height: 100vh;
          text-align: center;
          padding: 2rem;
        }
      </style>
      <div>
        <h1>Site indisponível para dispositivos móveis</h1>
        <p>Por favor, acesse por um computador ou tela maior.</p>
      </div>
    `);
  }
})();

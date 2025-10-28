<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Linha do Tempo de Periódicos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .timeline {
      position: relative;
      margin: 2rem auto;
      padding: 2rem 0;
      border-left: 3px solid #0d6efd;
    }
    .timeline-item {
      margin-left: 2rem;
      margin-bottom: 2rem;
      position: relative;
    }
    .timeline-item::before {
      content: "";
      position: absolute;
      left: -1.05rem;
      top: 0.3rem;
      width: 1rem;
      height: 1rem;
      background-color: #0d6efd;
      border-radius: 50%;
    }
    .pdf-list a {
      text-decoration: none;
      color: #0d6efd;
    }
    .pdf-list a:hover {
      text-decoration: underline;
    }
    .month-list {
      margin-left: 1rem;
    }
  </style>
</head>

<body class="container py-4">

  <h1 class="text-center mb-4">Linha do Tempo dos Periódicos</h1>
  <div id="timeline" class="timeline"></div>

  <script>
    async function carregarPeriodicos() {
      const resp = await fetch('listar.php');
      const data = await resp.json();
      const container = document.getElementById('timeline');
      container.innerHTML = '';

      const anos = Object.keys(data).sort();

      anos.forEach(ano => {
        const anoDiv = document.createElement('div');
        anoDiv.className = 'timeline-item';

        let mesesHTML = '';
        const meses = Object.keys(data[ano]).sort();

        meses.forEach(mes => {
          const pdfs = data[ano][mes].map(pdf =>
            `<li><a href="periodicos/${ano}/${mes}/${pdf}" target="_blank">${pdf}</a></li>`
          ).join('');
          mesesHTML += `
            <div class="month-list mb-3">
              <strong>Mês ${mes}</strong>
              <ul class="pdf-list">${pdfs}</ul>
            </div>
          `;
        });

        anoDiv.innerHTML = `
          <h4>${ano}</h4>
          ${mesesHTML}
        `;

        container.appendChild(anoDiv);
      });
    }

    carregarPeriodicos();
  </script>

</body>
</html>

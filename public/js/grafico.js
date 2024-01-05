const ctx = document.getElementById('count');
const inDesde = document.getElementById('inp-desde');
const inHasta = document.getElementById('inp-hasta');
const inDesdeInv = document.getElementById('inp-desde-invalid');
const btn = document.getElementById('btn-process');

document.querySelector('a.inicio').classList.add('fw-semibold');

inDesde.value = dayjs().subtract(1, 'month').format('YYYY-MM-DD');
inHasta.value = dayjs().format('YYYY-MM-DD');

let conf = {};

btn.addEventListener('click', procesar);

async function procesar(){
    await validaFechas();
    if(!conf.fechasValidas)
    return;
    await getData();
    await loadData();
}

async function validaFechas(){
    const fdesde = dayjs(inDesde.value);
    const fhasta = dayjs(inHasta.value);
    if(fdesde.isAfter(fhasta)){
        inDesdeInv.textContent='Fecha inválida';
        return {...conf, fechasValidas: false}
    } else {
        inDesdeInv.textContent='';
    }
    const ydiff = fhasta.diff(fdesde, 'y');
    const mdiff = fhasta.diff(fdesde, 'M');
    if(ydiff > 1){
        conf = {...conf, fechasValidas: true, vista: 'AÑO', fdesde, fhasta}
        return ;
    }
    if(mdiff > 1){
        conf = {...conf, fechasValidas: true, vista: 'MES', fdesde, fhasta}
        return ;
    }
    conf = {...conf, fechasValidas: true, vista: 'DIA', fdesde, fhasta}
    return ;
}

async function getData(){
    const fdesde = conf.fdesde.format('YYYY-MM-DD');
    const fhasta = conf.fhasta.format('YYYY-MM-DD');
    try {
        const res = await fetch('https://appmapy.facitec.edu.py/api/detections/getBetweenDates',{
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({desde: fdesde, hasta: fhasta, vista: conf.vista})
        });
        const data = await res.json();
        if(!data.ok){
            throw new Error('Algo salió mal');
        }
        conf = {...conf, data:[...data.data]};
        return ;
    } catch (err) {
        console.error(err);
    }
}

async function loadData(){
    const {data, vista} = conf;
    chart.data.labels = data.map(i => i.label);
    chart.data.datasets[0] = {
        data: data.map(i=>i.cant),
        label: 'CANTIDAD DE VEHÍCULOS POR ' + vista
    };
    chart.update();
}

const chart = new Chart(ctx,{
    type: 'line',
});

procesar();

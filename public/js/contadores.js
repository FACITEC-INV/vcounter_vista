const inDate = document.getElementById('inp-date');
const sDia = document.getElementById('span-dia');
const sCarsDia = document.getElementById('span-carsDia');
const sTrucksDia = document.getElementById('span-trucksDia');
const sMes = document.getElementById('span-mes');
const sCarsMes = document.getElementById('span-carsMes');
const sTrucksMes = document.getElementById('span-trucksMes');
const sAnho = document.getElementById('span-anho');
const sCarsAnho = document.getElementById('span-carsAnho');
const sTrucksAnho = document.getElementById('span-trucksAnho');

inDate.value = dayjs().format('YYYY-MM-DD');

inDate.addEventListener('change', getData)

async function getData(){
    try {
        const res = await fetch('http://127.0.0.1:8000/api/detections/getByDate',{
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({date: dayjs(inDate.value).format('YYYY-MM-DD')})
        });
        const dat = await res.json();
        if(!dat.ok){
            throw new Error('Error al recuperar los datos')
            } else {
            show(dat.data);
        }
    } catch (err) {
       console.error(err);
    }
}

function show(dat){
    sDia.textContent = dat.dia;
    sMes.textContent = dat.mes;
    sAnho.textContent = dat.anho;
    sCarsDia.textContent = dat.carsDia;
    sCarsMes.textContent = dat.carsMes;
    sCarsAnho.textContent = dat.carsAnho;
    sTrucksDia.textContent = dat.trucksDia;
    sTrucksMes.textContent = dat.trucksMes;
    sTrucksAnho.textContent = dat.trucksAnho;
}

getData();

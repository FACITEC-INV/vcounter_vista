const inDate = document.getElementById('inp-date');
const sDia = document.getElementById('span-dia');
const sVehicDia = document.getElementById('span-vehicDia');
const sMes = document.getElementById('span-mes');
const sVehicMes = document.getElementById('span-vehicMes');
const sAnho = document.getElementById('span-anho');
const sVehicAnho = document.getElementById('span-vehicAnho');

inDate.value = dayjs().format('YYYY-MM-DD');

inDate.addEventListener('change', getData)

async function getData(){
    try {
        const res = await fetch('https://appmapy.facitec.edu.py/api/detections/getByDate',{
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
    sVehicDia.textContent = dat.vehicDia;
    sVehicMes.textContent = dat.vehicMes;
    sVehicAnho.textContent = dat.vehicAnho;
}

getData();

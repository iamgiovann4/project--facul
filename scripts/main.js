
// variaveis globais

let nav = 0
let clicked = null
let events = localStorage.getItem('events') ? JSON.parse(localStorage.getItem('events')) : []


// variavel do modal:
const newEvent = document.getElementById('newEventModal')
const deleteEventModal = document.getElementById('deleteEventModal')
const backDrop = document.getElementById('modalBackDrop')
const eventTitleInput = document.getElementById('eventTitleInput')
// --------
const calendar = document.getElementById('calendar') // div calendar:
const weekdays = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'] //array with weekdays:

//funções

function openModal(date) {
  clicked = date
  const eventDay = events.find((event) => event.date === clicked)


  if (eventDay) {
    document.getElementById('eventText').innerText = eventDay.title
    deleteEventModal.style.display = 'block'


  } else {
    newEvent.style.display = 'block'

  }

  backDrop.style.display = 'block'
}

//função load() será chamada quando a pagina carregar:

async function load() {
  const eventsDB = await loadEvent();
  const date = new Date();

  //mudar titulo do mês:
  if (nav !== 0) {
    date.setMonth(new Date().getMonth() + nav)
  }

  const day = date.getDate()
  const month = date.getMonth()
  const year = date.getFullYear()



  const daysMonth = new Date(year, month + 1, 0).getDate()
  const firstDayMonth = new Date(year, month, 1)


  const dateString = firstDayMonth.toLocaleDateString('pt-br', {
    weekday: 'long',
    year: 'numeric',
    month: 'numeric',
    day: 'numeric',
  })


  const paddinDays = weekdays.indexOf(dateString.split(', ')[0])

  //mostrar mês e ano:
  document.getElementById('monthDisplay').innerText = `${date.toLocaleDateString('pt-br', { month: 'long' })}, ${year}`


  calendar.innerHTML = ''

  // criando uma div com os dias:

  for (let i = 1; i <= paddinDays + daysMonth; i++) {
    const dayS = document.createElement('div')
    dayS.classList.add('day')

    const dayString = `${month + 1}/${i - paddinDays}/${year}`

    //condicional para criar os dias de um mês:

    if (i > paddinDays) {
      dayS.innerText = i - paddinDays

      const eventDay = eventsDB.find(event => event.date === dayString)

      if (i - paddinDays === day && nav === 0) {
        dayS.id = 'currentDay'
      }

      if (eventDay) {
        const eventDiv = document.createElement('div')
        eventDiv.classList.add('event')
        eventDiv.innerText = eventDay.title
        dayS.appendChild(eventDiv)

      }

      dayS.addEventListener('click', () => openModal(dayString))

    } else {
      dayS.classList.add('padding')
    }


    calendar.appendChild(dayS)
  }
}

function closeModal() {
  eventTitleInput.classList.remove('error')
  newEvent.style.display = 'none'
  backDrop.style.display = 'none'
  deleteEventModal.style.display = 'none'

  eventTitleInput.value = ''
  clicked = null
  load()

}

async function insertEvents(event) {
  const formData = new FormData();
  
  // Enviar apenas o último evento
  formData.append('title', event.title);
  formData.append('date', event.date);

  try {
    const response = await fetch('./backend/insertEvent.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();

    if (result.success) {
      alert('Seu evento ' + result.data.title + ' foi cadastrado com sucesso!');
    } else {
      console.error('Erro ao cadastrar o evento:', result);
    }
  } catch (error) {
    console.error('Erro na solicitação:', error);
  }
}

function saveEvent() {
  if (eventTitleInput.value) {
    eventTitleInput.classList.remove('error');

    const newEvent = {
      date: clicked,
      title: eventTitleInput.value
    };

    insertEvents(newEvent); // Passa apenas o último evento
    events.push(newEvent);  // Adiciona o evento ao array local
    localStorage.setItem('events', JSON.stringify(events));
    closeModal();
  } else {
    eventTitleInput.classList.add('error');
  }
}


function deleteEvent() {

  events = events.filter(event => event.date !== clicked)
  localStorage.setItem('events', JSON.stringify(events))
  closeModal()
}

// botões 

function buttons() {
  document.getElementById('backButton').addEventListener('click', () => {
    nav--
    load()

  })

  document.getElementById('nextButton').addEventListener('click', () => {
    nav++
    load()

  })

  document.getElementById('saveButton').addEventListener('click', () => saveEvent())

  document.getElementById('cancelButton').addEventListener('click', () => closeModal())

  document.getElementById('deleteButton').addEventListener('click', () => deleteEvent())

  document.getElementById('closeButton').addEventListener('click', () => closeModal())

}
buttons()
load()

async function loadEvent() {
  const response = await fetch('./backend/selectEvent.php')
  const result = await response.json()
  if (result?.success) {
    return result.data || [];
  }else{
    alert('Erro ao listar o evento');
    return [];
  } 
}//funcao;

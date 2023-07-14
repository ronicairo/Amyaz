const quizData = [
    {
        question: "Traduis la phrase suivante : c'est Muḥand",
        a: "D Muḥand",
        b: "Muḥand",
        c: "Netta d Muḥand",
        d: "Neccin d Muḥand",
        correct: "a",
    },
    {
        question: "Que signifie la phrase suivante : Massin d amedyaz",
        a: "Massin, c'est le poète",
        b: "Massin n'est pas un poète",
        c: "Massin est un poète",
        d: "Le poète est Massin",
        correct: "c",
    },
    {
        question: "Traduis la phrase suivante : le chat et la souris jouent",
        a: "Mucc d tɣarḍact d ttiraren",
        b: "Mucc d tɣarḍact ttiraren",
        c: "Mucc ttiraren d tɣarḍact ",
        d: "Mucc tɣarḍact d ttiraren",
        correct: "b",
    },
    {
        question: "Que signifie la phrase suivante : waǧi d amzenzay",
        a: "Ce n'est pas une vendeuse",
        b: "C'est une vendeuse",
        c: "C'est un vendeur",
        d: "Ce n'est pas un vendeur",
        correct: "d",
    },
    {
        question: "Dans la phrase suivante, la particule d se prononce-t-elle à l'oral : tikaṛṛusin d tibarcanent. Pour info, la phrase signifie les voitures sont noires.",
        a: "Oui, la particule d se prononce à l'oral car le d suit l'adjectif féminin tibarcanent.",
        b: "Non, la particule d ne se prononce pas à l'oral car le d précède l'adjectif féminin tibarcanent.",
        c: "Oui, la particule d se prononce à l'oral car le d suit l'adjectif féminin tibarcanent. À l'écrit, la particule d ne s'écrit pas quand le d précède l'adjectif féminin tibarcanent.",
        d: "Non, la particule d ne se prononce pas à l'oral car le d précède l'adjectif féminin tibarcanent. Toutefois, la particule d doit s'écrire à l'écrit tout de même.",
        correct: "d",
    },
];

const quiz= document.getElementById('quiz')
const answerEls = document.querySelectorAll('.answer')
const questionEl = document.getElementById('question')
const a_text = document.getElementById('a_text')
const b_text = document.getElementById('b_text')
const c_text = document.getElementById('c_text')
const d_text = document.getElementById('d_text')
const submitBtn = document.getElementById('submit')


let currentQuiz = 0
let score = 0

loadQuiz()

function loadQuiz() {

    deselectAnswers()

    const currentQuizData = quizData[currentQuiz]

    questionEl.innerText = currentQuizData.question
    a_text.innerText = currentQuizData.a
    b_text.innerText = currentQuizData.b
    c_text.innerText = currentQuizData.c
    d_text.innerText = currentQuizData.d
}

function deselectAnswers() {
    answerEls.forEach(answerEl => answerEl.checked = false)
}

function getSelected() {
    let answer
    answerEls.forEach(answerEl => {
        if(answerEl.checked) {
            answer = answerEl.id
        }
    })
    return answer
}


submitBtn.addEventListener('click', () => {
    const answer = getSelected()
    if(answer) {
       if(answer === quizData[currentQuiz].correct) {
           score++
       }

       currentQuiz++

       if(currentQuiz < quizData.length) {
           loadQuiz()
       } else {
           quiz.innerHTML = `
           <h2>Tu as répondu correctement à ${score} question(s) sur ${quizData.length}</h2>

           <button onclick="location.reload()" style="width:100%">Refaire</button>
           `
       }
    }
})
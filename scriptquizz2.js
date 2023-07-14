const quizData = [
    {
        question: "Traduis la phrase suivante : je n'ai pas bu du thé",
        a: "swiɣ atay",
        b: "war swiɣ ca atay",
        c: "ca swiɣ war atay",
        d: "war swiɣ war atay",
        correct: "b",
    },
    {
        question: "Traduis la phrase suivante : je n'ai pas déjeuné aujourd'jui, je jeûne",
        a: "mmucerweɣ ass-a, war ttẓumeɣ ca",
        b: "ca mmucriweɣ war ass-a, ttẓumeɣ",
        c: "war mmucriweɣ war ass-a, ttẓumeɣ",
        d: "war mmucriweɣ ca ass-a, ttẓumeɣ",
        correct: "d",
    },
    {
        question: "Traduis la phrase suivante : il n'a pas trouvé ses chausses dans la chambre",
        a: "war yufa ca tisira nnes deg uxxam",
        b: "war yufa war tisira nnes deg uxxam",
        c: "yufa ca tisira nnes deg uxxam",
        d: "ca yufa war tisira nnes deg uxxam",
        correct: "a",
    },
    {
        question: "Traduis la phrase suivante : ce n'est pas moi",
        a: "nec war d ǧi",
        b: "ǧi war d nec",
        c: "war ǧi d nec",
        d: "d nec war ǧi",
        correct: "c",
    },
    {
        question: "Comment formule-t-on la négation en rifain ?",
        a: "ca + VERBE + war",
        b: "VERBE + war + ca",
        c: "war + VERBE + ca",
        d: "war + ca + VERBE",
        correct: "c",
    },
    {
        question: "Quelle est la particule obligatoire pour former la négation en rifain ?",
        a: "war",
        b: "ca",
        c: "aucune",
        d: "les deux",
        correct: "a",
    },
    {
        question: "Quelle est la particule facultative (non-obligatoire) pour former la négation en rifain ?",
        a: "war",
        b: "ca",
        c: "aucune",
        d: "les deux",
        correct: "b",
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
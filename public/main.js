// Получаем элементы DOM
const form = document.getElementById("textForm");
const resultDiv = document.getElementById("result");
const historyList = document.getElementById("history");
const my_btn = document.getElementById("test_btn");
let currentHistory = [];

let currentLang = null;

document.addEventListener("DOMContentLoaded", async () => {
  await loadHistory();
});

form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(form);
  const text = formData.get("text"); // Получаем текст из формы

  try {
    const response = await axios.post("/check", { text });
    console.log(response);
    renderResult(response.data);
    currentLang = response.data.language;
    console.log(response.data);
  } catch (error) {
    console.error("Ошибка при отправке:", error);
  }
});

document
  .querySelector('textarea[name="text"]')
  .addEventListener("input", (e) => {
    if (!currentLang) return;

    const newText = e.target.value;
    const wrongChars = Array.from(newText).filter((char) =>
      currentLang === "ru" ? /[A-Za-z]/.test(char) : /[А-Яа-я]/.test(char)
    );

    renderResult({
      language: currentLang,
      errors: wrongChars,
      input: newText,
    });
  });

function renderResult(data) {
  let resultHtml = "";
  const my_set = new Set(data.errors);
  if (my_set.size > 0) {
    data.input.split("").forEach((char) => {
      if (my_set.has(char)) {
        resultHtml += `<span class="highlight">${char}</span>`;
      } else {
        resultHtml += `<span>${char}</span>`;
      }
    });
  } else {
    resultHtml = data.input;
  }
  resultDiv.innerHTML = resultHtml;
}

async function loadHistory() {
  try {
    const response = await axios.get("/history");
    const newHistory = response.data;

    if (JSON.stringify(newHistory) !== JSON.stringify(currentHistory)) {
      currentHistory = newHistory;
      renderHistory(newHistory);
    }
  } catch (error) {
    console.error("История сломалась :", error);
  }
}

function renderHistory(history) {
  if (!history || history.length === 0) {
    historyList.innerHTML = "<li>История пуста</li>";
    return;
  }

  console.log(history);

  historyList.innerHTML = ""; // очищаем список

  history.forEach((item) => {
    const li = document.createElement("li");

    // Создаем основной текст
    const textSpan = document.createElement("span");
    const my_mistakes = new Set(item.errors);
    // Если есть ошибки - подсвечиваем символы

    Array.from(item.input_text).forEach((char) => {
      const Span = document.createElement("span");
      Span.textContent = char;

      if (my_mistakes.has(char)) {
        Span.className = "highlight";
      }
      textSpan.appendChild(Span);
    });

    const metaSpan = document.createElement("span");
    metaSpan.style.color = "#666";
    metaSpan.style.fontSize = "0.9em";
    metaSpan.textContent = ` (${item.language}, ошибок: ${item.errors.length})`;

    li.appendChild(textSpan);
    li.appendChild(metaSpan);
    historyList.appendChild(li);
  });
}

setInterval(loadHistory, 2000);

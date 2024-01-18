const buttons = document.querySelectorAll(".faq-toggle");
const text = document.querySelectorAll(".faq-text");

buttons.forEach((button) => {
  button.addEventListener("click", () => {
    button.previousElementSibling.classList.toggle("active-txt");
    button.parentNode.classList.toggle("active");
  });
});
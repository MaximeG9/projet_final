const chevrons = document.querySelectorAll(".faq-toggle");
const text = document.querySelectorAll(".faq-text");

chevrons.forEach((chevron) => {
  chevron.addEventListener("click", () => {
    chevron.previousElementSibling.classList.toggle("active-txt");
    chevron.parentNode.classList.toggle("active");
  });
});
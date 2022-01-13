
//For "How to search bar" dropwdown function.
function drop() {
  var element = document.getElementById("myPanel");
  var plusminus = document.getElementsByClassName("accordion");

  plusminus[0].classList.toggle("active"); //toggle active class på accordion, dvs göra + till - :)

  if (!element.classList.contains("panelOpen")) {
    element.classList.add("panelOpen");
    element.style.maxHeight = element.scrollHeight + "px"; //ger myPanel ett ID ett max-height efter hur lång texten är.
  } else {
    element.classList.remove("panelOpen");
    element.style.maxHeight = null;
  }
}
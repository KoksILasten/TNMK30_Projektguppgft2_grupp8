function drop() {
  var element = document.getElementById("myPanel");
  var plusminus = document.getElementsByClassName("accordion");

  plusminus[0].classList.toggle("active");

  if (!element.classList.contains("panelOpen")) {
    element.classList.add("panelOpen");
    element.style.maxHeight = element.scrollHeight + "px";
  } else {
    element.classList.remove("panelOpen");
    element.style.maxHeight = null;
  }
}

function required_fill() {
  var x = document.getElementById("userinput").required;
}

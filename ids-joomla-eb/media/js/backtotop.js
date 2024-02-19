const backtotoplink = document.getElementById('gotop')
const onScroll = () => {
  const scroll = document.documentElement.scrollTop
  if (scroll > 0) {
    backtotoplink.classList.add("show");
  } else {
    backtotoplink.classList.remove("show")
  }
}
window.addEventListener('scroll', onScroll)

function goTop() {
  document.documentElement.scrollTop = 0;
}
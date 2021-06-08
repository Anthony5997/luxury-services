$(document).ready(function(){
    $("#statistics").hide();
    $(".candidates").hide();
    $(".clients").hide();
    $(".jobOffers").hide();
    $(".candidacy").hide();
  
// NavBar admin 
  $( ".btnStatistics" ).click(function() {
    $("#statistics").toggle();
    $(".candidates").hide();
    $(".clients").hide();
    $(".jobOffers").hide();
    $(".candidacy").hide();

    
  });

  $( ".btnCandidates" ).click(function() {
    $("#candidates").toggle();
    $(".statistics").hide();
    $(".clients").hide();
    $(".jobOffers").hide();
    $(".candidacy").hide();
  });

  $( ".btnClients" ).click(function() {
    $("#clients").toggle();
    $(".statistics").hide();
    $(".candidates").hide();
    $(".jobOffers").hide();
    $(".candidacy").hide();
  });

  $( ".btnJobOffers" ).click(function() {
    $("#jobOffers").toggle();
    $(".statistics").hide();
    $(".candidates").hide();
    $(".clients").hide();
    $(".candidacy").hide();
  });

  $( ".btnCandidacy" ).click(function() {
    $("#candidacy").toggle();
    $(".statistics").hide();
    $(".candidates").hide();
    $(".clients").hide();
    $(".jobOffers").hide();

  });
  });
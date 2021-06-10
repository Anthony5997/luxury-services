$(document).ready(function(){
  $(".candidates").hide();
  $(".clients").hide();
  $(".jobOffers").hide();
  $(".candidacy").hide();
  $(".footer").hide();
  $(".experience").hide();
  $(".jobType").hide();
  $(".jobCategory").hide();
  $(".offer_display").hide();

  


  // NavBar admin 
  $( ".btnStatistics" ).click(function() {
    $("#statistics").toggle();
    $(".candidates").hide();
    $(".clients").hide();
    $(".jobOffers").hide();
    $(".candidacy").hide();
    $(".experience").hide();
    $(".jobType").hide();
    $(".jobCategory").hide();


    
  });

  $( ".btnCandidates" ).click(function() {
    $("#candidates").toggle();
    $(".statistics").hide();
    $(".clients").hide();
    $(".jobOffers").hide();
    $(".candidacy").hide();
    $(".accesFormNotes").hide();
    $(".accesSeeNotes").hide();
    $(".experience").hide();
    $(".jobType").hide();
    $(".jobCategory").hide();


  });

  $( ".btnClients" ).click(function() {
    $("#clients").toggle();
    $(".statistics").hide();
    $(".candidates").hide();
    $(".jobOffers").hide();
    $(".candidacy").hide();
    $(".accesFormNotes").hide();
    $(".accesSeeNotes").hide();
    $(".experience").hide();
    $(".jobType").hide();
    $(".jobCategory").hide();

  });

  $( ".btnJobOffers" ).click(function() {
    $("#jobOffers").toggle();
    $(".statistics").hide();
    $(".candidates").hide();
    $(".clients").hide();
    $(".candidacy").hide();
    $(".experience").hide();
    $(".jobType").hide();
    $(".jobCategory").hide();

  });

  $( ".btnCandidacy" ).click(function() {
    $("#candidacy").toggle();
    $(".statistics").hide();
    $(".candidates").hide();
    $(".clients").hide();
    $(".jobOffers").hide();
    $(".experience").hide();
    $(".jobType").hide();
    $(".jobCategory").hide();

  });
  $( ".btnExperience" ).click(function() {
    $("#experience").toggle();
    $(".statistics").hide();
    $(".candidates").hide();
    $(".clients").hide();
    $(".jobOffers").hide();
    $(".jobType").hide();
    $(".jobCategory").hide();
    $(".candidacy").hide();
  });

  $( ".btnJobType" ).click(function() {
    $("#jobType").toggle();
    $(".statistics").hide();
    $(".candidates").hide();
    $(".clients").hide();
    $(".experience").hide();
    $(".jobOffers").hide();
    $(".jobCategory").hide();
    $(".candidacy").hide();
  });
  $( ".btnJobCategory" ).click(function() {
    $("#jobCategory").toggle();
    $(".statistics").hide();
    $(".candidates").hide();
    $(".clients").hide();
    $(".experience").hide();
    $(".jobOffers").hide();
    $(".jobType").hide();
    $(".candidacy").hide();
  });


  $( ".buttonAccesAddNotes" ).click(function() {
    let id = this.getAttribute('id')
    $(`#accesFormNotes${id}`).toggle();

    
  });
  $( ".buttonAccesSeeNotes" ).click(function() {
    let id = this.getAttribute('id')
    $(`#accesSeeNotes${id}`).toggle();

    
  });

  $( "#offer_display_btn" ).click(function() {
    $(`.offer_display`).toggle();
  });
});
$(document).ready(function(){
	//sign-up tabs
	if($('.list-tabs').length) {
		$('.list-tabs a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
			$('.list-tabs a').parent("li").removeClass("active");
			$(this).parent("li").addClass("active");
		})
	}

	//Категории рубрик
	function formatState (state) {
		var parentHeadings = ["Родительская категория1", "Родительская категория2"]; //массив категорий для вывода в селект

		if (!state.id) {
		  return state.text;
		}
		var $state = $(
		  '<span>' + state.text + ' <span class="parents">/ ' + parentHeadings[0] + ',&nbsp;' + parentHeadings[1] + '</span>' + '</span>'
		);
		return $state;
	};
	
	//select2
	if($(".select2").length) {
		$(".select2").select2({
			language: "ru",
			placeholder: "Выберите рубрику",
			closeOnSelect: false,
			multiple: true,
			templateResult: formatState,
		});
	}
});

$(document).ready(function() {
	$("#doer-tel").mask("(999) 999-9999");
	$("#customer-tel").mask("(999) 999-9999");

	$("#doer-tab form").on('submit', function(e) {

	e.preventDefault();

	if(! validateEmail($("#doer-email").val())) {
		$("#doer-email + .form-validator").html('Некорректный email');
		$("#doer-email").addClass('error');
	}
	else {
		var xhr = new XMLHttpRequest();
		var form = document.querySelector('#doer-tab form');
		var data = new FormData(form);
		xhr.open('POST', '');
		xhr.send(data);
		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4) {
				if(xhr.status == 201) {
					$("#modal-message").modal("show");
					form.reset();
				}
				else {
					$("#modal-error").modal("show");
				}
			}
		}
	}
	});

	$("#customer-tab form").on('submit', function(e) {

		e.preventDefault();

		if(! validateEmail($("#customer-email").val())) {
			$("#customer-email + .form-validator").html('Некорректный email');
			$("#customer-email").addClass('error');
		}
		else {
			var xhr = new XMLHttpRequest();
			var form = document.querySelector('#customer-tab form');
			var data = new FormData(form);
			xhr.open('POST', '');
			xhr.send(data);
			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 201) {
						$("#modal-message").modal("show");
						form.reset();
					}
					else {
						$("#modal-error").modal("show");
					}
				}
			}
		}
	});

    function validateEmail(email) {
		var re = /\S+@\S+\.\S+/;
		return re.test(email);
	}

	function insertOptions(type, input, list) {
		var SELECT_SIZE = 5;
		var xhr = new XMLHttpRequest();
		xhr.open('GET', `/api.php?type=${type}&startswith=${input.value}`);
		xhr.send();
		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4) {
				var data = JSON.parse(xhr.response);
				list.innerHTML = '';
				data.forEach( function(element, index) {
					var option = document.createElement('li');
					option.innerHTML = element;
					list.appendChild(option);
				});
				list.size = Math.min(data.length, SELECT_SIZE);
			}
		}
	}

	var customerCityWrapper = document.querySelector(
		'#customer-city-wrapper');
	var customerCityInput = document.querySelector(
		'#customer-city-input');
	var customerCityList = document.querySelector('#customer-city-list');
	var customerRubricWrapper = document.querySelector(
		'#customer-rubric-wrapper');
	var customerRubricInput = document.querySelector(
		'#customer-rubric-input');
	var customerRubricList = document.querySelector(
		'#customer-rubric-list');

	var doerCityWrapper = document.querySelector(
		'#doer-city-wrapper');
	var doerCityInput = document.querySelector(
		'#doer-city-input');
	var doerCityList = document.querySelector('#doer-city-list');
	var doerRubricWrapper = document.querySelector(
		'#doer-rubric-wrapper');
	var doerRubricInput = document.querySelector(
		'#doer-rubric-input');
	var doerRubricList = document.querySelector(
		'#doer-rubric-list');

	makeLiveSearch(customerCityInput, customerCityList, 
		customerCityWrapper, 'city');
	makeLiveSearch(customerRubricInput, customerRubricList,
		customerRubricWrapper, 'rubric');

	makeLiveSearch(doerCityInput, doerCityList, 
		doerCityWrapper, 'city');
	makeLiveSearch(doerRubricInput, doerRubricList,
		doerRubricWrapper, 'rubric');

	$(".form-choice-close").on('click', function(e) {
		e.target.closest('.form-choice-item').style({'display': 'none'});
	})






	function makeLiveSearch(input, list, wrapper, type) {
		var START_MATCH_LEN = 1;

		input.onfocus = update;

		input.onkeyup = update;

		window.addEventListener('click', function(e) {
			list.classList.add('d-none');
		})

		list.onclick = function(e) {
			input.value = e.target.innerHTML;
			list.classList.add('d-none');
		}

		wrapper.onclick = function(e) {			
			e.stopPropagation();
		}

		function update() {
			if(input.value.length >= START_MATCH_LEN) {
				list.classList.remove('d-none');
				insertOptions(type, input, list);
			}
			else {
				list.classList.add('d-none');
			}
		}
		
	}



});


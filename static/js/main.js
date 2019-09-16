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
});

$(document).ready(function() {

	$("#doer-tel").mask("(000) 000-0000", 
		{'clearIfNotMatch': true});
	$("#customer-tel").mask("(000) 000-0000",
		{'clearIfNotMatch': true});

	addSlidingNavBar();

	addUserFormScript('doer');
	addUserFormScript('customer');

	var counterBoxes = {
		'customer':	{
			'belarus_customer_counter':
				document.querySelector('#belarus-customer-counter'),
			'russia_customer_counter':
				document.querySelector('#russia-customer-counter'),
			'ukraine_customer_counter':
				document.querySelector('#ukraine-customer-counter'),
			'kazakhstan_customer_counter':
				document.querySelector('#kazakhstan-customer-counter'),
		},
		'doer': {
			'belarus_doer_counter': 
				document.querySelector('#belarus-doer-counter'),
			'russia_doer_counter':
				document.querySelector('#russia-doer-counter'),
			'ukraine_doer_counter':
				document.querySelector('#ukraine-doer-counter'),
			'kazakhstan_doer_counter':
				document.querySelector('#kazakhstan-doer-counter'),
		}
		
	};

	updateCounters('customer');
	updateCounters('doer');


	function addUserFormScript(type) {
		var cityWrapper = document.querySelector(
		`#${type}-city-wrapper`);
		var cityInput = document.querySelector(
			`#${type}-city-input`);
		var cityList = document.querySelector(
			`#${type}-city-list`);
		var rubricWrapper = document.querySelector(
			`#${type}-rubric-wrapper`);
		var rubricInput = document.querySelector(
			`#${type}-rubric-input`);
		var rubricList = document.querySelector(
			`#${type}-rubric-list`);
		var rubricChoice = document.querySelector(
			`#${type}-rubric-choice`);

		addResetErrors();

		$(`#${type}-tab form`).on('submit', function(e) {
			registerUser(e, type);
		});

		makeLiveSearch(cityInput, cityList, 
			cityWrapper, 'city');
		makeLiveSearch(rubricInput, rubricList,
			rubricWrapper, 'rubric', rubricChoice);
	}

	function registerUser (e, type) {
		var rubricChoice = document.querySelector(
			`#${type}-rubric-choice`);

		e.preventDefault();

		if(! validateEmail($(`#${type}-email`).val())) {
			$(`#${type}-email + .form-validator`).html('Некорректный email');
			$(`#${type}-email`).addClass('error');
		}
		else if(! getChosenRubrics(rubricChoice)) {
			$(`#${type}-rubric-wrapper .form-validator`).html('Некорректная рубрика');
			$(`#${type}-rubric-input`).addClass('error');
		}
		else {
			var xhr = new XMLHttpRequest();
			var form = document.querySelector(`#${type}-tab form`);
			var cityInput = document.querySelector(`#${type}-city-input`);
			var data = new FormData(form);
			data.append(`${type}-rubrics`, 
				getChosenRubrics(rubricChoice) ? 
				getChosenRubrics(rubricChoice) : '');
			xhr.open('POST', '');
			xhr.send(data);

			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 201) {
						$("#modal-message").modal("show");
						form.reset();
						$(".form-choice").html('');
						updateCounters(type);
					}
					else if(xhr.status == 500) {
						$("#modal-server-error").modal("show");
						form.reset();
						$(".form-choice").html('');
					}
					else if(xhr.status == 403) {
						$("#modal-bad-request").modal("show");
						form.reset();
						$(".form-choice").html('');
					}
					else {
						var response = JSON.parse(xhr.responseText);
						var fieldsForValidation = [`${type}-city`, `${type}-rubric`];
						fieldsForValidation.forEach(function(field) {
							if(response.hasOwnProperty(field)) {
								$(`#${field}-input`).addClass('error');
								$(`#${field}-input ~ .form-validator`).html(
									response[field]);
							}
						});
					}
				}
			}
		}
	}

	
	function makeLiveSearch(input, list, wrapper, type, choiceBox = null) {
		var START_MATCH_LEN = 3;

		input.onfocus = update;

		input.onkeyup = update;

		window.addEventListener('click', function(e) {
			list.addEventListener('click', function(e) {
				e.stopPropagation();
			});
			if(e.target != input) {
				list.classList.add('d-none');
			}
			
		})

		list.addEventListener('mouseup', function(e) {
			var title = e.target.innerHTML;
			if(type == 'city') {
				input.value = title;
				list.classList.add('d-none');
			}
			else if(type == 'rubric') {
				if(titleInBox(title, choiceBox)) return;
				choiceBox.appendChild(createChoiceItem(
					title));
			}
			
		});

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

	function createChoiceItem(text) {
		var item = document.createElement('li');
		item.classList.add('form-choice-item');

		var itemText = document.createElement('span');
		itemText.classList.add('form-choice-text');
		itemText.innerHTML = text;

		var itemCloser = document.createElement('span');
		itemCloser.classList.add('form-choice-close');
		itemCloser.innerHTML = '&#10006';

		itemCloser.onclick = function(e) {
			item.closest('.form-choice')
			.removeChild(item);
		};

		item.appendChild(itemText);
		item.appendChild(itemCloser);

		return item;
	}

	function titleInBox(title, choiceBox) {
		var items = choiceBox.querySelectorAll(
			".form-choice-text");
		for (item of items) {
			if(item.innerHTML == title) return true;
		}
		return false;
	}

	function validateEmail(email) {
		var re = /\S+@\S+\.\S+/;
		return re.test(email);
	}

	function insertOptions(type, input, list) {
		var SELECT_SIZE = 5;
		var QUERY_LIMIT = 10;
		var xhr = new XMLHttpRequest();
		xhr.open('GET', `/api.php?type=${type}
			&startswith=${input.value}
			&limit=${QUERY_LIMIT}`);
		xhr.send();
		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4) {
				if(xhr.responseText == '') {
					var data = [];
				}
				else {
					var data = JSON.parse(xhr.responseText);
				}
				
				list.innerHTML = '';
				data.forEach( function(element, index) {
					var option = document.createElement('li');
					if(type == 'rubric') {
							option.innerHTML = 
					`${element['name']}/${element['parent_name']}`;
					} 
					else if(type == 'city') {
						option.innerHTML = 
							`${element['name']}/${element['region_name']}/${element['country_name']}`;
					}
				
					list.appendChild(option);
				});
				list.size = Math.min(data.length, SELECT_SIZE);
			}
		}
	}

	function getChosenRubrics(choiceBox) {
		var items = choiceBox.querySelectorAll(
			'.form-choice-text');
		if(items.length == 0) return false;
		var arr = [];
		for (item of items) {
			arr.push(item.innerHTML.slice(0, 
				item.innerHTML.indexOf('/')));
		}
		return arr.join('***');
	}

	function addResetErrors() {
		var inputs = document.querySelectorAll(
			'.form-control');

		for (input of inputs) {
			input.addEventListener('click', function(e) {
				var validationElem = e.target.closest(
					'.form-group').querySelector('.form-validator');
				e.target.classList.remove('error');
				validationElem.innerHTML = '';
			})
		}
	}

	function updateCounters(type) {
		var xhr = new XMLHttpRequest();
		xhr.open('GET', `api.php?type=${type}&counters=true`);
		xhr.send();
		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4) {
				var counterData = JSON.parse(xhr.responseText);
				for (key in counterBoxes[type]) {
					counterBoxes[type][key].innerHTML = counterData[key];
				}
			}
		}
	}

	function addSlidingNavBar() {
		var navContainer = document.querySelector('#nav-container')
		var navBar = document.querySelector('#nav-bar');
		var toggle = document.querySelector('#nav-toggle');
		
		toggle.onmouseover = function () {
			navBar.classList.remove('d-none');
		};
		navContainer.onmouseleave = function () {
			navBar.classList.add('d-none');
		}
	}
});


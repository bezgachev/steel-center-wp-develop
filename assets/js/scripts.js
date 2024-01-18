$(function () {
	// function forMe() {}  if ($('.yourclass').length) { forMe(); }



	function heightChangeTextarea() {
		$('.black textarea').on('input keyup paste', function () {
			var $el = $(this),
				offset = $el.innerHeight() - $el.height();

			if ($el.innerHeight() < this.scrollHeight) {
				// Grow the field if scroll height is smaller
				$el.height(this.scrollHeight - offset);
			} else {
				// Shrink the field and then re-set it to the scroll height in case it needs to shrink
				$el.height(1);
				$el.height(this.scrollHeight - offset);
			}
			maps();
		});

	}

	if ($('.black').length) { heightChangeTextarea(); }


	function replacementPlaceholderByRadioButton() {
		$('input[type="radio"]').click(function () {
			var valueRadio = $(this).val();
			if (valueRadio === 'phone') {
				$('input[type="tel"]').attr('placeholder', 'Телефон*');
				$('input[type="email"]').attr('placeholder', 'E-mail');
			} else {
				$('input[type="tel"]').attr('placeholder', 'Телефон');
				$('input[type="email"]').attr('placeholder', 'E-mail*');
			}
		});
	} replacementPlaceholderByRadioButton();

	function repositionBlock() {
		$(window).on('load resize', function () {
			if (document.documentElement.clientWidth <= 992) {
				$('.technical-specifications').insertAfter('.price-list__container');
			}
			else {
				$('.technical-specifications').insertAfter('.price-list .price-list__body_table');
			}
		});
	}
	if ($('.technical-specifications').length) { repositionBlock(); }

	function click() {
		const clickAddActiveErrorMess = $('.error-mess');

		clickAddActiveErrorMess.on("click", function (e) {
			$(this).addClass('active');
			setTimeout(function () {
				clickAddActiveErrorMess.removeClass('active');
			}, 1000);
		});

	}
	if ($('.error-mess').length) { click(); }


	function animationSvg() {
		let len = 0;
		let speed = 1;
		let delay = 1;

		const paths = document.querySelectorAll('.path_line');

		paths.forEach(element => {
			let elementLen = element.getTotalLength();
			len += elementLen;
		})

		paths.forEach(element => {
			let elementLen = element.getTotalLength();
			let duration = elementLen / len * speed;

			element.style.animationDuration = `${duration}s`;
			element.style.animationDelay = `${delay}s`;

			element.setAttribute('stroke-dasharray', elementLen);
			element.setAttribute('stroke-dashoffset', elementLen);
			delay += duration;
		})
	}
	if ($('.path_line').length) { animationSvg(); }


	// const scrollController = {
	// 	scrollPosition: 0,
	// 	disabledScroll() {
	// 		scrollController.scrollPosition = window.scrollY;
	// 		document.body.style.cssText = `
	// 			overflow: inherit;
	// 			position: fixed;
	// 			top: -${scrollController.scrollPosition}px;
	// 			left: 0;
	// 			height: 100vh;
	// 			width: 100vw;
	// 			padding-right: ${window.innerWidth - document.body.offsetWidth}px
	//     `;
	// 		document.documentElement.style.scrollBehavior = 'unset';
	// 	},
	// 	startScroll() {
	// 		document.body.style.cssText = '';
	// 		window.scroll({ top: scrollController.scrollPosition });
	// 		document.documentElement.style.scrollBehavior = '';			
	// 	},
	// }

	const scrollController = {
		fScrollTop: $('html'),
		disabledScroll() {
			let scrolled = $(window).scrollTop();
			localStorage.setItem('DachaDecorScrollTop', scrolled);
			scrollController.fScrollTop.scrollTop(scrolled);

			let scrollWidth = window.innerWidth - document.documentElement.clientWidth;
			scrollController.fScrollTop.css({
				// overflow: "hidden",
				overflow: "inherit",
				// paddingRight: `-${scrollWidth}px`,
			});
		},
		startScroll() {
			scrollController.fScrollTop.removeAttr('style');
			setTimeout(() => {
				localStorage.removeItem('DachaDecorScrollTop');
			}, 200);
		}
	}

	function PopUp() {


		function getName(title, btnName) {
			popupTitle.html(title);
			popupBtn.val(btnName);
		}

		function getNameInfo(title, btnName) {
			popupTitleInfo.html(title);
			popupSubTitleInfo.html(subtitle);
		}

		//ин-фо об отправке
		let checkOk = {
			title: 'Заявка успешно отправлена',
			subtitle: 'Совсем скоро мы свяжемся с Вами'
		};
		let checkError = {
			title: 'Что-то пошло не так',
			subtitle: 'Не удалось отправить заявку. Пожалуйста, попробуйте снова'
		};

		const btnOpen = $('.open-popup-js[data-btn]');
		const btnClose = $('.popup > .btn-close, #overlay');
		const wrapperPopup = $('.popup');
		const overlay = $('#overlay');

		// text popup
		const popupTitle = $('.popup__title');
		const popupBtn = $('.popup').find('input[type="submit"]');

		var itemDataAttr;

		const namePopup = {
			price: {
				btn: 'Запросить расчет',
				title: 'Расчет стоимости',
				subtitle: true,
				btnName: 'Запросить расчет',
				class: ''
			},
			call: {
				btn: 'Связаться с нами',
				title: 'Обратная связь',
				subtitle: true,
				btnName: 'Отправить заявку',
				class: 'call'
			},
			offer: {
				btn: 'Заказать услугу',
				title: 'Заказать услугу',
				subtitle: false,
				btnName: 'Отправить заявку',
				class: ''
			},
			calculation: {
				btn: 'Оставьте заявку',
				title: 'Заявка на расчет',
				subtitle: true,
				btnName: 'Отправить запрос',
				class: ''
			}
		}

		btnOpen.on("click", function () {
			var dataAttr = $(this).data('btn');
			if (namePopup.hasOwnProperty(dataAttr)) {
				itemDataAttr = namePopup[dataAttr];
				if (itemDataAttr.subtitle == false) {
					$('.popup__subtitle-container').css("display", "none");
					$('.popup__fields').css("margin-top", "0");
				} else {
					$('.popup__subtitle-container, .popup__fields').removeAttr('style');
				}
				if (itemDataAttr['class'] !== undefined) {
					$('.popup').removeClass(itemDataAttr.class)
					$('.popup').addClass(itemDataAttr.class)
				}
				else {
					$('.popup').removeClass(itemDataAttr.class)
				}
				getName(itemDataAttr.title, itemDataAttr.btnName);
			}
			funcOpenPopup();
		});


		btnClose.on("click", function () {
			funcClosePopup();
		});

		function funcOpenPopup() {
			scrollController.disabledScroll();
			overlay.fadeIn();
			wrapperPopup.fadeIn();
		}

		function funcClosePopup() {
			overlay.fadeOut();
			wrapperPopup.fadeOut();

			scrollController.startScroll();

			setTimeout(() => {
				$('.popup').removeClass(itemDataAttr.class)
			}, 500);

		}

		$(document).on('keydown', function (e) {
			if (e.key === 'Escape') {
				funcClosePopup();
			}
		});

	} PopUp();

	function akkardion() {
		const akkardionFunc = function (wrapperAkkardion, blockAkkardion) {
			wrapperAkkardion.find(blockAkkardion).on('click', function () {
				blockAkkardion.not(this).removeClass('active');
				$(this).addClass('active');
			});
		}

		const wrapperAkkardionFooter = $(".footer__descr");
		const blockAkkardionFooter = $(".akkardion");
		akkardionFunc(wrapperAkkardionFooter, blockAkkardionFooter);

		const wrapperFleet = $(".fleet__container");
		const blockFleet = $(".fleet__item");

		blockFleet.mouseenter(function () {
			$(this).trigger("click");
			akkardionFunc(wrapperFleet, blockFleet);
		});
	} akkardion();


	function transitionBlock() {
		// функция выезда блока справа
		const transitionBlockFunc = function (btnOpen, burgerContainer, btnClose, position, overlay) {
			const funcOpen = function () {
				burgerContainer.css(position, "0px");
				$('body').addClass('stop-scrolling');
				overlay.addClass('active');
				overlay.css({
					display: 'block'
				});
				setTimeout(function () {
					overlay.css({
						opacity: '1'
					});
				}, 10);
			}
			const funcClose = function () {
				burgerContainer.css(position, "-100vw");
				$('body').removeClass('stop-scrolling');
				overlay.removeClass('active');
				overlay.removeAttr("style");
			}

			btnOpen.on("click", function () {
				funcOpen();
			});

			const burgerCloseFunc = function (btnClose) {
				btnClose.on("click", function () {
					funcClose();
				});
			}

			burgerCloseFunc(btnClose);
			burgerCloseFunc(overlay);

			// MatchMedia------------------------------------
			function checkingMatchMedia(minScreenWidths = 991, trueFuncName, falseFuncName) {
				// const mediaQuery = window.matchMedia('(min-width: 991px)');
				const mediaQuery = window.matchMedia(`(min-width: ${minScreenWidths}px)`);
				function handleTabletChange(e) {
					// Проверить, что media query будет true
					if (e.matches) {
						trueFuncName();
					}
					// else {
					//     falseFuncName();
					// }
				}
				mediaQuery.addListener(handleTabletChange); // Слушать события
				handleTabletChange(mediaQuery); // Начальная проверка
			}

			checkingMatchMedia(991, funcClose);
		}
		const positionRight = "right";
		const positionLeft = "left";
		const positionBottom = "bottom";


		const overlay = $('.overlay-menu');
		const btnOpenBurger = $('.burger');
		const containerMobMenu = $('.nav');
		const btnCloseBurger = $('.close');
		transitionBlockFunc(btnOpenBurger, containerMobMenu, btnCloseBurger, positionRight, overlay);
	} transitionBlock();


	//фиксированное header меню
	$(window).on('load resize', function () {
		let pos = $(".header").offset();
		if (pos.top > 110) {
			$('header').addClass('active');
		}

		$(window).scroll(function () {
			if ($(window).scrollTop() > 10) {
				$('header').addClass('active');
			} else {
				$('header').removeClass('active');
			}
		});
	});

	// в меню добовляется класс arrow если есть вложенные элименты
	if ($('.nav__wrapper ul li:has(ul)')) {
		$('.nav__wrapper ul li:has(ul)').addClass('arrow');
	}
	if ($('.nav__wrapper:has(ul)')) {
		$('.nav__wrapper:has(ul)').addClass('main-arrow');
	}

	//открытие в бургере списка
	const dropDownMenuBurger = $('.nav__wrapper.main-arrow div');
	dropDownMenuBurger.on("click", function (event) {
		// event.preventDefault();
		$('.nav__wrapper.main-arrow').toggleClass('active');
	});




	$(window).scroll(function () {
		if ($(window).scrollTop() > 300) {
			$('#scroll_top').show();
		} else {
			$('#scroll_top').hide();
		}
	});

	$('#scroll_top').click(function () {
		$('html, body').animate({ scrollTop: 0 }, 600);
		return false;
	});

	// Swiper главная Примеры последних работ
	if (document.body.contains(document.querySelector('.works-slider'))) {
		new Swiper('.works-slider', {
			slidesPerView: 1,
			// effect: 'fade',
			grabCursor: true,
			navigation: {
				nextEl: '.works-slider-next',
				prevEl: '.works-slider-prev'
			},
			// nested: true,
			pagination: {
				el: '.works-slider-pagination',
				type: 'fraction'
			},
		});
		new Swiper('.work-img', {
			slidesPerView: 1,
			effect: 'fade',
			grabCursor: true,
			nested: true,
			pagination: {
				el: '.work-img-pagination',
				type: 'bullets',
				clickable: true,
			},
			// Предзагрузка картинок
			preloadImages: true,
			lazy: {
				loadOnTransitionStart: true, // Подгружать на старте переключения слайда
				loadPrevNext: true, // Подгрузить предыдущую и следующую картинки
			},
			// Слежка за видимыми слайдами
			watchSlidesProgress: true,
			// Добавление класса видимым слайдам
			watchSlidesVisibility: true,
		});
	}

	// Swiper для таблиц со скролом сверху
	if (document.body.contains(document.querySelector('.swiper-table'))) {
		const sliders = document.querySelectorAll('.swiper-table');
		// console.log(sliders.dataset.mobile);
		let mySwipers;

		function mobileSliderTable() {
			sliders.forEach((el) => {
				if (window.innerWidth <= 768 && el.dataset.mobile == 'false') {
					mySwipers = new Swiper(el, {
						slidesPerView: 'auto',
						watchOverflow: true,
						scrollbar: {
							// el: el.querySelector('.table-scrollbar'),
							// el: el.parentElement.parentElement.parentElement.querySelector('.table-scrollbar'),
							el: el.closest('section').querySelector('.table-scrollbar'),
							// el: '.table-scrollbar',
							// Возможность перетаскивать скролл
							draggable: true
						},
						grabCursor: true,
						watchOverflow: true,
					});
					el.dataset.mobile = 'true';
				}

				if (window.innerWidth > 768) {
					el.dataset.mobile = 'false';

					if (el.classList.contains('swiper-initialized')) {
							mySwipers.destroy();
						}					
				}
			});
		}		
		mobileSliderTable();

		window.addEventListener('resize', () => {
			mobileSliderTable();
		});
	}

	// Swiper категории
	// if (document.body.contains(document.querySelector('.categories'))) {
	// 	const slider = document.querySelector('.slider-container');
	// 	let mySwiper;
	// 	function mobileSlider() {
	// 		if (window.innerWidth <= 992 && slider.dataset.mobile == 'false') {
	// 			mySwiper = new Swiper(slider, {
	// 				slidesPerView: 'auto',
	// 				spaceBetween: 10,
	// 				slideClass: 'category',
	// 				scrollbar: {
	// 					el: '.swiper-scrollbar',
	// 					draggable: true
	// 				},
	// 				breakpoints: {
	// 					992: {
	// 						spaceBetween: 0,
	// 					},
	// 				}
	// 			});
	// 			slider.dataset.mobile = 'true';
	// 		}

	// 		const mediaQuery = window.matchMedia('(min-width: 992px)');
	// 		function handleTabletChange(e) {
	// 			// Проверить, что media query будет true
	// 			if (e.matches) {
	// 				slider.dataset.mobile = 'false';
	// 				if (slider.classList.contains('swiper-initialized')) {
	// 					mySwiper.destroy();
	// 				}
	// 			}
	// 		}
	// 		// Слушать события
	// 		mediaQuery.addListener(handleTabletChange);
	// 		// Начальная проверка
	// 		handleTabletChange(mediaQuery);

	// 	}

	// 	mobileSlider();

	// 	window.addEventListener('resize', () => {
	// 		mobileSlider();
	// 	});
	// }


	// !Слайдер popup ======================================
	if (document.querySelector('.gallery__item')) {

		btnPopupOpen = $('.gallery__item');
		btnClose = $('.close-button');

		btnPopupOpen.on("click", function () {
			$('body').addClass('stop-scrolling');
			$('.modal-img').fadeIn(300);

			var slideId = $(this).attr('id');
			openFullscreenSwiper(slideId);
		});
		btnClose.click(function () {
			$('body').removeClass('stop-scrolling');
			$('.modal-img').fadeOut(300);
		});
		// закрыть попап Escape
		$(document).on('keydown', function (e) {
			if (e.key === 'Escape') {
				btnClose.click();
			}
		});
	}
	// !Слайдер popup ======================================
	function openFullscreenSwiper(initialSlideNumber) {
		new Swiper('.popup-img', {
			initialSlide: initialSlideNumber - 1,
			navigation: {
				nextEl: '.popup-next',
				prevEl: '.popup-prev',
			},
			// Навигация
			// Буллеты, текущее положение, прогрессбар
			pagination: {
				el: '.popup-pagination',
				type: 'fraction',
			},

			grabCursor: true,
			// Управление клавиатурой
			keyboard: {
				enabled: true,
				onlyInViewport: true,
				pageUpDown: true,
			},

			slidesPerView: 1,
			// Отключение функционала если слайдов меньше чем нужно
			watchOverflow: true,
			// Количество пролистываемых слайдов
			slidesPerGroup: 1,

			// Эффекты переключения слайдов. Листание
			effect: 'fade',
			// Обновить свайпер при изменении элементов слайдера
			observer: true,
			// Обновить свайпер при изменении родительских элементов слайдера
			observeParents: true,
			// Обновить свайпер при изменении дочерних элементов слайда
			observeSlideChildren: true,

			// Отключить предзагрузка картинок
			preloadImages: false,
			// Lazy Loading
			// (подгрузка картинок)
			lazy: {
				// Подгружать на старте
				// переключения слайда
				loadOnTransitionStart: true,
				// Подгрузить предыдущую
				// и следующую картинки
				loadPrevNext: true,
			},
			// Слежка за видимыми слайдами
			watchSlidesProgress: true,
			// Добавление класса видимым слайдам
			watchSlidesVisibility: true,

		});
	}

});


function changetDate() {
	const startDate = new Date('2022-12-28');  //устанавливаем дату начала интервала 
	const now = new Date(); //определяем текущую дату
	const delta = Math.trunc((+now - +startDate) / 14 / 24 / 3600 / 1000); // вычисляем количество трёхдневных интервалов которые прошли с момента startDate
	const resDate = startDate;
	resDate.setDate(resDate.getDate() + delta * 14);
	//итоговая дата -- это стартовая дата + количево вычислиных трёхдневных интервалов, вычисленных на предыдущем шаге, умноженые на 3
	const dateTag = document.querySelector('.price-list__info_descr p b'); //находим тег, в котором должна оказаться новая дата
	dateTag.textContent = resDate.toLocaleString('ru', { day: '2-digit', month: '2-digit', year: 'numeric' });
}
if ($('.price-list .price-list__info_descr').length) { changetDate(); }


// if (document.body.contains(document.querySelector('.swiper-table'))) {
// 	let mySlider = new Swiper('.swiper-table', {
// 		slidesPerView: 'auto',
// 		watchOverflow: true,
// 		scrollbar: {
// 			el: '.table-scrollbar',
// 			// Возможность перетаскивать скролл
// 			draggable: true
// 		},
// 	});
// }

// Swiper akkardion slider
if (document.body.contains(document.querySelector('.accordian-swiper'))) {
	function mobileAccordianSwiper() {
		const swiperSliderImg = $(".accordian-swiper .swiper-slide img");
		var arrSlideSwiper = swiperSliderImg.map(function () {
			return $(this).attr("src");
		});

		const slider = document.querySelector('.accordian-swiper');
		const sliderMin = document.querySelector('.accordian-swiper-thumbs');
		let accordianSwiper;
		let accordianSwiperMin;

		function mobileSliderAccordian() {

			if (window.innerWidth <= 675 && slider.dataset.mobile == 'false' && sliderMin.dataset.mobile == 'false') {
				accordianSwiperMin = new Swiper(sliderMin, {
					slidesPerView: 6,
					virtual: {
						slides: (function () {
							let slide = arrSlideSwiper;
							for (let i = 0; i < slide.lenght - 1; i++) {
								slide.push(`${slide[i]}`);
							}
							return slide;
						}()),
						renderSlide: function (slide, index) {
							return `
								<div class="swiper-slide">
									<img src="${slide}" alt="Картинка">
								</div>
							`;
						},
					},
				});
				accordianSwiper = new Swiper(slider, {
					thumbs: {
						swiper: accordianSwiperMin,
					},
				});
				slider.dataset.mobile = 'true';
				sliderMin.dataset.mobile = 'true';
			}

			if (window.innerWidth > 675) {
				slider.dataset.mobile = 'false';
				sliderMin.dataset.mobile = 'false';

				if (slider.classList.contains('swiper-initialized')) {
					accordianSwiper.destroy();
					accordianSwiperMin.virtual.removeAllSlides();
					accordianSwiperMin.destroy();
				}
			}
		}
		mobileSliderAccordian();

		window.addEventListener('resize', () => {
			mobileSliderAccordian();
		});



		function aboutSlider() {
			const blockFleet = $(".accordian-swiper .swiper-wrapper .swiper-slide");

			function ShowBlock() {
				setTimeout(() => {
					$('.accordian .swiper-wrapper .swiper-slide.active .swiper-slide_title').css({
						"opacity": "1",
						"top": "0",
						"left": "0"
					});
				}, 200);
			}

			$(window).on('load', function () {
				if (document.documentElement.clientWidth >= 675) {
					blockFleet.first().trigger("click");
					ShowBlock();
				}
			});

			// MatchMedia------------------------------------
			function checkingMatchMedia1(minScreenWidths = 991, trueFuncName, falseFuncName) {
				// const mediaQuery = window.matchMedia('(min-width: 991px)');
				const mediaQuery = window.matchMedia(`(min-width: ${minScreenWidths}px)`);
				function handleTabletChange(e) {
					// Проверить, что media query будет true
					if (e.matches) {
						trueFuncName();
					}
					else {
						falseFuncName();
					}
				}
				mediaQuery.addListener(handleTabletChange); // Слушать события
				handleTabletChange(mediaQuery); // Начальная проверка
			}

			const funcActive = function () {
				blockFleet.on({
					mouseenter: function () {
						// console.log('навели');
						blockFleet.not(this).removeClass("active");
						$('.swiper-slide_title').removeAttr('style');
						$(this).addClass("active");
						$(this).parent().addClass("active");
						ShowBlock();
					},
					mouseleave: function () {
						// console.log('отвели');
						$('.swiper-slide_title').not(this).removeAttr('style');
						$(this).removeClass("active");
						$(this).parent().removeClass("active");
					},
					click: function () {
						$('.swiper-slide_title').not(this).removeAttr('style');
						blockFleet.not(this).removeClass("active");
						$(this).addClass("active");
						$(this).parent().addClass("active");
					}
				});
			}

			const funcStop = function () {
				$('.accordian .swiper-wrapper .swiper-slide .swiper-slide_title').removeAttr('style');
				blockFleet.removeClass("active");
				blockFleet.parent().removeClass("active");
			}

			checkingMatchMedia1(675, funcActive, funcStop);

		}
		aboutSlider();
	}
	mobileAccordianSwiper();
}



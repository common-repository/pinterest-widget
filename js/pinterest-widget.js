
window.addEventListener('load', () => {
	var feedContainer = document.getElementById('pinterest_widget_feed');
	var allFeeds = feedContainer.getElementsByClassName('feed_item');
	var pinFeedCount = parseInt(feedContainer.getAttribute('data-pin-count'));
	var feedArray = Array();

	if (pinFeedCount === 1) {
		var colWidth = '94%';
		var feedPerRow = 1;
		allFeeds[0].style.margin = '3%';

	} else if (pinFeedCount === 2) {

		colWidth = '47%';
		var feedPerRow = 2;
		allFeeds[0].style.margin = '1%';
		allFeeds[1].style.margin = '1%';

	} else {
		colWidth = '30.5%';
		var feedPerRow = 3;
	}

	for (var i in allFeeds) {
		if (i >= 0) {
			var link = allFeeds[i].getElementsByTagName('a')[0].getAttribute('href');
			var image = allFeeds[i].getElementsByTagName('img')[0].getAttribute('src');
			var title = allFeeds[i].getAttribute('data-feed-title');
			allFeeds[i].innerHTML = '';
			allFeeds[i].style.width = colWidth;
			var feedLink = document.createElement('a');
			feedLink.setAttribute('class', 'feed_link');
			feedLink.setAttribute('href', link);
			feedLink.style.width = '100%';
			feedLink.setAttribute('target', '_blank');
			feedLink.setAttribute('title', title);

			var img = document.createElement('img');
			img.setAttribute('class', 'feed_image');
			img.setAttribute('src', image);


			feedLink.appendChild(img);
			allFeeds[i].appendChild(feedLink);
			allFeeds[i].setAttribute('data-feed-width', colWidth);
			let feedImg = allFeeds[i].querySelector('a').querySelector('img');

			let feedContainer = allFeeds[i].parentElement;


			allFeeds[i].addEventListener('mouseleave', () => {


				feedImg.style.zIndex = '100';
				feedImg.style.width = feedImg.getAttribute('data-mas-wd') + 'px';
				feedImg.style.height = feedImg.getAttribute('data-mas-ht') + 'px';
				feedImg.style.marginLeft = feedImg.getAttribute('data-mas-ml') + 'px';
			});
			allFeeds[i].addEventListener('mouseover', () => {

				let htWtRatio = feedImg.offsetHeight / feedImg.offsetWidth;

				feedImg.style.zIndex = '500';
				feedImg.style.display = 'block';
				if (null == feedImg.getAttribute('data-mas-ht') && null == feedImg.getAttribute('data-mas-wd')) {
					feedImg.setAttribute('data-mas-ht', feedImg.offsetHeight);
					feedImg.setAttribute('data-mas-wd', feedImg.offsetWidth);
					feedImg.setAttribute('data-mas-ml', feedImg.style.marginLeft);
				}

				feedImg.style.width = feedContainer.offsetWidth + 'px';
				feedImg.style.height = (feedContainer.offsetWidth * htWtRatio) + 'px';
			});

		}


		feedContainer.parentElement.style.display = '';
	}




	const mas = new jsMasonry('.pinterest_feeds', {
		elSelector: 'img',
		elMargin: 2,
	})

});


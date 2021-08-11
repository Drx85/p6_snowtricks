const PaginatorPerPage = 10;
let offset = 0;
function increment() {
	offset = offset + PaginatorPerPage;
}
window.onload = () => {
	let link = document.querySelector("[data-load]");
	link.addEventListener("click", function (e) {
		e.preventDefault();
		fetch(this.getAttribute("href"), {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
				"Content-Type": "application/json"
			},
			body: JSON.stringify({"offset": offset})
		}).then(
			(response) => response.json()
		).then((data) => {
			for (c of data.comments) {
				const container = document.getElementById("comments");
				
				const timestamp = c.created_at * 1000;
				const dateObject = new Date(timestamp);
				const dateFormat = dateObject.toLocaleDateString();
				
				if (!c.userPicture)
					c.userPicture = "default.jpg";
				
				container.innerHTML +=
					'<li>' +
						'<div class="row mb-4">' +
							'<div class="col-xxl-1 col-xl-2 col-lg-3 col-md-4 col-sm-5 d-flex justify-content-sm-end">' +
								'<img src="../uploads/images/user/' + c.userPicture + '" class="mb-3 rounded-circle" alt="Avatar" width="100" height="100">' +
							'</div> ' +
							'<div class="col-xxl-11 col-xl-10 col-lg-9 col-md-8 col-sm-7">' +
								'<h5>Par ' + c.username + ', le ' + dateFormat + '</h5>' +
								'<p class="mt-3">' + c.message + '</p>' +
							'</div>' +
						'</div>' +
					'</li>';
				if (data.comments.length < PaginatorPerPage)
					this.parentElement.remove();
			}
		}).catch(e => alert(e));
	});
}
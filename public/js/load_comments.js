const PaginatorPerPage = 10;
let offset = 0;
function increment() {
	offset = offset + PaginatorPerPage;
}
addEventListener("load", function () {
	let link = document.querySelector("[data-load]");
	link.addEventListener("click", function (e) {
		e.preventDefault();
		let url = this.getAttribute("href") + "&offset=" + offset;
		fetch(url).then(
			(response) => response.json()
		).then((data) => {
			for (c of data.comments) {
				const container = document.getElementById("comments");
				const timestamp = c.created_at * 1000;
				const dateObject = new Date(timestamp);
				const dateFormat = dateObject.toLocaleDateString();
				
				if (!c.user.picture)
					c.user.picture = "default.jpg";
				
				if (data.userRoles.includes("ROLE_ADMIN")) {
				container.innerHTML +=
					'<li>' +
						'<div class="row mb-4" id="' + c.id + '">' +
							'<div class="col-xxl-1 col-xl-2 col-lg-3 col-md-4 col-sm-5 d-flex justify-content-sm-end">' +
								'<img src="../uploads/images/user/' + c.user.picture + '" class="mb-3 rounded-circle" alt="Avatar" width="100" height="100">' +
							'</div> ' +
							'<div class="col-xxl-11 col-xl-10 col-lg-9 col-md-8 col-sm-7">' +
								'<h5>Par ' + c.user.username + ', le ' + dateFormat +
									'<a href="../admin/delete/comment/' + c.id + '"' +
									'data-delete-js data-token="' + c.csrfToken.value +'">' +
									'<button class="border-0 bg-white"><i class="far fa-trash-alt text-info h5"></i></button>' +
									'</a>' +
								'</h5>' +
								'<p class="mt-3">' + c.message + '</p>' +
							'</div>' +
						'</div>' +
					'</li>';
				} else {
					container.innerHTML +=
						'<li>' +
							'<div class="row mb-4" id="' + c.id + '">' +
								'<div class="col-xxl-1 col-xl-2 col-lg-3 col-md-4 col-sm-5 d-flex justify-content-sm-end">' +
									'<img src="../uploads/images/user/' + c.user.picture + '" class="mb-3 rounded-circle" alt="Avatar" width="100" height="100">' +
								'</div> ' +
								'<div class="col-xxl-11 col-xl-10 col-lg-9 col-md-8 col-sm-7">' +
									'<h5>Par ' + c.user.username + ', le ' + dateFormat +
									'</h5>' +
									'<p class="mt-3">' + c.message + '</p>' +
								'</div>' +
							'</div>' +
						'</li>';
				}
				if (data.comments.length < PaginatorPerPage)
					this.parentElement.remove();
			}
			let links = document.querySelectorAll("[data-delete-js]");
			for (link of links) {
				link.addEventListener("click", function (e) {
					e.preventDefault();
					if (confirm("Confirmer la suppression (immédiate et définitive) ?")) {
						fetch(this.getAttribute("href"), {
							method: "DELETE",
							headers: {
								"X-Requested-With": "XMLHttpRequest",
								"Content-Type": "application/json"
							},
							body: JSON.stringify({"_token": this.dataset.token})
						}).then(
							(response) => response.json()
						).then((data) => {
							if (data.success !== undefined) {
								let el = document.getElementById(data.success);
								el.remove();
							} else
								alert(data.error);
						}).catch(e => alert(e));
					}
				})
			}
		}).catch(e => alert(e));
	});
});




import { extend } from 'flarum/extend';
import app from 'flarum/app';
import Modal from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Stream from 'flarum/common/utils/Stream';

export default class addTipModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);
	this.post = this.attrs.post;
	this.user_id = this.attrs.user_id;
	this.sender_id = this.attrs.sender_id;
	this.div_tip_id = this.attrs.div_tip_id;
	this.address = this.attrs.address;
	this.tips_amount = this.attrs.tips_amount;
	this.objtips = {
		'post_id': this.post.data.id,
		'user_id': this.user_id,
		'tips_amount': this.tips_amount
	};

  }

  className() {
    return 'addTipModal Modal--small';
  }

  title() {
    return app.translator.trans(`chen-nbdomain-login.forum.tip_title`);
  }
  
  content() {
    return (
      <div className="Modal-body">
          <div className="Form Form--centered">
		    <div id="pay" style="width:100% !important; height:220px; align: center;"></div>
          </div>
      </div>
    );
  }

  onready() {
	  console.log(this.div_tip_id['dom']['innerHTML']);
	  //console.log(this.address + ' ' + this.tips_amount + ' user_id = ' + this.user_id + ' post_id = ' + this.post.data.id);
	  //opay.init({ containerID: "pay"});
	  //document.getElementById("div_tip"+this.userid).outerHTML = 'Thank you for your tips';
	  opay.changeContainer("pay");
	  opay.setUI({close:false});	  
	  //console.log(opay);
        let reqBody = {
          to: [{ address: this.address, value: 1000 }],
          expire: Date.now() + 120 * 1000,
          broadcast: false
        };
        let req = {
          pay_request: {
            data: reqBody
          }
        };
        opay.request(req, e => {
          let result = e;
          console.log(result);
          if (result.code == 0) {
            const res = "Success, url=tx://" + result.txhash;
            console.log(res);
			this.div_tip_id['dom']['innerHTML'] = app.translator.trans('chen-nbdomain-login.forum.tip_thanks');
			app.modal.close();
			var xhttp = new XMLHttpRequest(); var xtoken = new XMLHttpRequest();

			xtoken.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				  console.log(this);
				}
			};
			xtoken.open("POST", app.forum.attribute('baseUrl') + "/api/token", true);
			xtoken.setRequestHeader('Content-Type', 'application/json');
			xtoken.setRequestHeader('Authorization: Token ; userId=1;');
			xtoken.send(JSON.stringify({
				user_id: this.user_id,
				post_id: this.post.data.id,
				sender_id: this.sender_id,
				tips_amount: this.tips_amount,
				trx: result.txhash
			}));
			
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				  //window.location.reload();
				}
			};
			xhttp.open("POST", app.forum.attribute('baseUrl') + "/nbdomaintips" , true);
			xhttp.setRequestHeader('Content-Type', 'application/json');
			xhttp.send(JSON.stringify({
				user_id: this.user_id,
				post_id: this.post.data.id,
				sender_id: this.sender_id,
				tips_amount: this.tips_amount,
				trx: result.txhash
			}));
			/*const ndata = this.objtips;
			console.log(ndata);
			return this.post
				.save({ ndata })
				.then((post) => {
					m.redraw();
				})
				.catch((err) => {

					$('body').append(err);

					m.redraw();
				});			
			*/
            //document.querySelector("#status").innerText = res;
          } else {
			//this.div_tip_id['dom']['innerHTML'] = app.translator.trans('chen-nbdomain-login.forum.tip_cancel');
			app.modal.close();
			alert(app.translator.trans('chen-nbdomain-login.forum.tip_cancel'));
		  }
        });
	  
  }

  onsubmit(e) {
    e.preventDefault();
	console.log('disubmit loh');
  }  
}

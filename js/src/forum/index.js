import {extend, override} from 'flarum/extend';
import app from 'flarum/app';
import LogInButtons from 'flarum/components/LogInButtons';
import LogInButton from 'flarum/components/LogInButton';
import LogInModal from 'flarum/components/LogInModal';
import Button from 'flarum/common/components/Button';
import HeaderSecondary from "flarum/components/HeaderSecondary";
import Post from 'flarum/models/Post';
import Model from 'flarum/Model';
import User from "flarum/models/User";
//import addTipModal from './addTipModal';
import addTipSection from './addTipSection';



app.initializers.add('chen-nbdomain-login', () => {
    //Post.prototype.canLike = Model.attribute('canLike');
	Post.prototype.nbdomaintips = Model.hasMany('nbdomaintips');
	
	addTipSection();
	
    extend(HeaderSecondary.prototype, 'items', replaceSignupButton);	
	extend(LogInModal.prototype.onsubmit = function(e) { 
		e.preventDefault(); 
		this.loading = true;
		console.log(this.fields()['items']['submitku']);

		onLogin.call(this).then(
		  function(value) { /* code if successful */ },
		  function(error) { /* code if some error */ }
		); /*(this.fields()['items']['identification']['content']['children'][0]['attrs']['value']);*/
		//const var name = this.fields()['items']['identification']['content']['children'][0]['attrs']['value'];
		console.log('Nama domain ' + this.fields()['items']['identification']['content']['children'][0]['attrs']['value']); 
		
	});	

    function replaceSignupButton(items) {
        if (!items.has('signUp')) {
            return;
        }

        let signupUrl = "https://app.nbdomain.com/#/brand";
        items.replace('signUp',
            <a href={signupUrl} target="_blank" className="Button Button--link">
                {app.translator.trans('core.forum.header.sign_up_link')}
            </a>
        );
    }
	//
    extend(LogInModal.prototype, 'fields', function (fields) {
		//console.log(fields.items.identification['content']['children'][0]['attrs']['value']);
		var nbdomain = fields.items.identification['content']['children'][0]['attrs']['value'];
		fields.replace(
		  'identification',
		  <div className="Form-group">
			<input
			  className="FormControl"
			  name="identification"
			  type="text"
			  placeholder="NBDomain1"
			  bidi={this.identification}
			  disabled={this.loading}
			/>
		  </div>,
		  30
		); //
		fields.add(
		  'submitku',
		  <div id="elPay" style = "width:100%; height:0px;">
		  </div>,
		  -10
		);
		fields.remove("password");
		fields.remove("remember");
		//fields.remove("submit");
    });
	extend(LogInModal.prototype, 'footer', function (footer) {
		//console.log(footer[1]['children'][1]['attrs']);
		/*console.log(footer[0]);
		footer[0]['attrs']['className'] = 'opay';
		footer[0].id = 'elPay';
		footer[0].style = "width:300px;height:300px";
		footer[0]['tag'] = 'div';
		*/
		footer[1]['children'][1]['attrs']['onclick'] = function () {
			window.open("https://app.nbdomain.com/#/brand", "_blank");
		};
		delete footer[0]['children'][0]; //delete footer[1];				
	});	
});

async function onLogin(){
	console.log("---login start");
	await opay.init({debug: true,app:{name:"NBforum"} });
	opay.changeContainer("elPay");
	opay.setUI({close:false});	  
	const data_sign="hello";
	const name = this.fields()['items']['identification']['content']['children'][0]['attrs']['value'];
	const hash = opay.sha256(data_sign);
	const domain = await nblib.getDomain(name);
	console.log("---domain")
	if(domain) {
	  const pubKey = domain.info.owner_key;
	  const address = domain.info.owner;
		document.getElementById("elPay").style["min-height"] = "300px;";
        document.getElementById("elPay").style["padding-top"] = "300px";
		m.redraw();
	  const reqBody = {
		signer:address,
		data_hash:hash
	  }
	  opay.request({sign_request:{
		data:reqBody
	  }}, ret=> {
		const obj = ret;
		if(obj.code==0){
		  const sig = obj.body;
		  console.log("data_hash:",hash," sig:",sig," pubKey:",pubKey);
		  const verified = opay.verify(hash,sig,pubKey);
		  //alert(verified);
		  //if(elMsg){
		  //	elMsg.innerText = verified;
		  //}
		  if(obj.usedWallet)opay.setWallet(obj.usedWallet);
			
		  var xhttp = new XMLHttpRequest();
		  var xhttp2 = new XMLHttpRequest();
		  console.log("---1")
		  xhttp.onreadystatechange = function() {
		  	console.log("---2")
			if (this.readyState == 4 && this.status == 200) {
				console.log("---4")
				window.location.reload();
			//  xhttp2.open("GET", app.forum.attribute('baseUrl') + "/nbdomain-login?userid=" + name, true);
			//  xhttp2.send();
			  
			  console.log("---5")
			}
		  };
		  xhttp.open("GET", app.forum.attribute('baseUrl') + "/nbdomain-login?userid=" + name + "&address=" + address, true);
		  xhttp.send();
			console.log("---3")
		  /*xhttp2.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			  window.location.reload();
			}
		  };*/
		  
		} else {
			console.log('no nbdomain ' + obj.code);
			this.loading = false;
		}
	  })
	  
	} else {
		alert('Wrong NBdomain');
		document.getElementById("elPay").style["min-height"] = "0px;";
        document.getElementById("elPay").style["padding-top"] = "0px";

		const identification = this.identification();
		const password = this.password();
		const remember = this.remember();

		app.session
		.login({ identification, password, remember }, { errorHandler: this.onerror.bind(this) })
		.then(() => window.location.reload(), this.loaded.bind(this));
		console.log(document.getElementById('modal').childNodes);				
	}
}

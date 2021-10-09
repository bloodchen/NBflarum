import { extend } from 'flarum/extend';
import get from 'lodash/get';
import app from 'flarum/app';
import Modal from 'flarum/components/Modal';
import Button from 'flarum/components/Button';
import FieldSet from 'flarum/components/FieldSet';
import username from 'flarum/helpers/username';
import icon from 'flarum/helpers/icon';
import Stream from "flarum/utils/Stream";
import DiscussionList from "flarum/components/DiscussionList"

async function getUsername(userId){
	return new Promise(res=>{
		if(userId===0) res("Guest")
		else{
			app.store.find('users', userId).then(user=>res(user.data.attributes.displayName))
			}
	})
}

const find = (obj, clazz) =>
    obj && obj.children && obj.children.filter((e) => get(e, 'attrs.className', '').indexOf(clazz) !== -1)[0];

export default class fundModal extends Modal {
	oninit(vnode) {
    	super.oninit(vnode);
    	this.balance = null;
    	this.helpurl = app.forum.attribute("nbflarum-help");
    	fetch(app.forum.attribute('baseUrl')+"/nb/funddraw");
	}
	className() {
    	return 'Community Fund Modal--small';
  	}

  	title() {
    	return app.translator.trans('chen-nbdomain-login.forum.fund');
  	}
  	view(){
  		const nodeList = super.view();
  		const nodeContent = find(nodeList,"Modal-content");
  		const nodeClose = find(nodeContent,"Modal-close App-backControl");
  		console.log(nodeClose);
  		let help = Button.component(
                {
                    className: 'Button Button--link',
                    icon: 'fas fa-question',
      				title: "help",
      				onclick:()=>{
      					window.open(this.helpurl)
      				}
                },);
  		nodeClose.children.unshift(help);
  		return nodeList
  	}
  	content() {
  		if(!this.gettingBalance){
  			this.gettingBalance = true;
  			const self = this;
	  		fetch(app.forum.attribute('baseUrl')+"/nb/fundinfo").then(data=>data.json()).then(
	  			res=>{self.fundAddress=res.address;
	  				document.querySelector("#fundAddress").innerText = res.address;
	  			let balanceURL = `https://tnode.nbdomain.com/api/address/${this.fundAddress}/balance`;
		  		fetch(balanceURL).then(data=>data.json()).then(res=>{console.log(res);
		  			document.querySelector("#totalFund").innerText=(res.confirmed+res.unconfirmed)/100+" V"
		  			self.balance = (res.confirmed+res.unconfirmed)/100;
		  		});}
	  			);
  		}
  		if(!this.gettingTipList){
  			this.gettingTipList = true;
  			let url = app.forum.attribute('baseUrl')+"/tipsInfo?";
	    	let tt = (new Date);
	    	
	    	tt.setDate(1);
	    	const tFrom = tt.toISOString().split('T')[0];
	    	tt.setDate(32);tt.setDate(0);
	    	const tTo = tt.toISOString().split('T')[0];
	    	url += "from="+tFrom+"&to="+tTo;
	    	//console.log(url);
	    	fetch(url).then(data=>data.json()).then(res=>{
	    		console.log(res);
	    		for(let item of res.data){ 
	    			getUsername(item.attributes.senderId).then(data=>{
	    				document.querySelector("#fundUserList").innerText+=data+" ; ";
	    			});
	    		}
	    		let tnext = new Date;
	    		tnext.setDate(32);
	    		document.querySelector("#time-next-draw").innerText = tnext.toISOString().split('T')[0];
	    	});
  		}
    	

  		 return (
	      <div className="Modal-body">
	        <div className="Form ">
	          <div className="Form-group Form--centered">
	             <i class="icon fas fa-hand-holding-usd" style="font-size:80px;color:green"/>
	             <div id="totalFund" style="font-size:40px;color:golden;"/>
	             
	             
	          </div>
	          <div className="Form-group">
	          <ul style="padding-inline-start:10px;">
	             	<li>{app.translator.trans('chen-nbdomain-login.forum.fund_address')}:<div id="fundAddress" /></li>
	             	<li>{app.translator.trans('chen-nbdomain-login.forum.next_draw')}: <span id="time-next-draw" /></li>
	             	<li><a href={this.helpurl}>{app.translator.trans('chen-nbdomain-login.forum.rule')}</a></li>
	             </ul>
	            <h3>{app.translator.trans('chen-nbdomain-login.forum.eligible_users')}:</h3>
	            <div id="fundUserList"></div>
	          </div>
	          <div className="Form-group">
	            
	          </div>
	        </div>
	      </div>
    );
  	}
}
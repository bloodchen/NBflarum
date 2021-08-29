import { extend } from 'flarum/extend';
import app from 'flarum/app';
import CommentPost from 'flarum/components/CommentPost';
import Button from 'flarum/components/Button';
import FieldSet from 'flarum/components/FieldSet';
import username from 'flarum/helpers/username';
import icon from 'flarum/helpers/icon';
import Stream from "flarum/utils/Stream";

import addTipModal from './addTipModal';

async function getUsername(userId){
	return new Promise(res=>{
		if(userId===0) res("Guest")
		else{
			app.store.find('users', userId).then(user=>res(user.data.attributes.displayName))
			}
	})
}
export default function() {
		
	extend(CommentPost.prototype, 'footerItems', function(items) {		
		const post = this.attrs.post;
		const user = post.user();
		var curruser = app.session.user;
		var vcurruser = 0;
		if (typeof curruser === 'undefined') {
			//const opayaddressx = curruser.opayaddress();
			//console.log(Stream(this.attrs.user.web3address()));
		} else {
			vcurruser = curruser.data.id;
		}
		console.log('curuer:',curruser)
		console.log('user_id ' + vcurruser);
		//console.log(user);
		console.log(user['data']['attributes']['opayaddress']);
		const tiplist = "tiplist" + post.data.id; 
		const btnlist = "btnlist" + post.data.id; 
		const tiplistlabel = app.translator.trans(`chen-nbdomain-login.forum.tip_list`);
		const no_tips = app.translator.trans(`chen-nbdomain-login.forum.no_tips`);
		const url1 = app.forum.attribute('baseUrl') + "/posts/" + post.data.id + "/nbdomaintipslist?include=user";
        items.add(tiplist,(<div></div>))
        setTimeout(async ()=>{
        	//console.log(items)
        	const res1 = await fetch(url1);
        	const datax1 = (await res1.json()).data;
        	//console.log("datax1",datax1);
        	const elList = document.querySelector(".item-"+tiplist)
        	let htmlCode = ''
        	for(let item of datax1){   		
        		const name = await getUsername(item.attributes.senderId)
        		htmlCode+="<div><i class='icon fas fa-dollar-sign'></i> "+'<span>'+name+' tipped '+item.attributes.tipsAmount+'v  </span></div>';
        	}
        	if(elList)
        		elList.innerHTML = htmlCode
        	
        },500);
		
		
		const tip_text_id = "tip" + post.data.id; 
	    const tip_text_style = "width: 55px;";
		const tip_div = "div_tip" + post.data.id; 
		if (curruser !== user) {
		  items.add('nbdomaintips', (
			<div className="NBDomainTips" id={tip_div}>				
				{Button.component(
				  {
					className: 'Button Button--link',
					style: 'padding-bottom: 14px !important;',
					onclick: () => {
						const attrs = { user_id: user.data.id };
						attrs['div_tip_id'] = items.get('nbdomaintips');
						attrs['post'] = post;
						attrs['sender_id'] = vcurruser;
						attrs['tips_amount'] = parseInt(document.getElementById(tip_text_id).value);
						attrs['address'] = user['data']['attributes']['opayaddress'];
						app.modal.show(addTipModal, attrs);	  	
						console.log(items.get('nbdomaintips')['children'][2]['attrs']['value']);
						console.log(document.getElementById(tip_text_id).value);
						
					}
				  },
				  app.translator.trans(`chen-nbdomain-login.forum.tip_text`)
				
				)}&nbsp;<input type="number" id={tip_text_id} style={tip_text_style} min="2" value="2" />
			</div>
		  ));
		}
  });
}

function dicoba(datak) {
	console.log(datak);
}

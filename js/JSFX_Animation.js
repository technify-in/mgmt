/*******************************************************************
*
* File    : JSFX_Animation.js © JavaScript-FX.com
*
* Created : 2000/05/16
*
* Author  : Roy Whittle www.Roy.Whittle.com
*           
* Purpose : To create animated rollovers
*
* History
* Date         Version        Description
*
* 2001-03-17	2.0		Converted for javascript-fx
***********************************************************************/
/*** Create some global variables ***/
var AnimationRunning = false; /*** Global state of animation ***/
var FrameInterval    = 30;   /*** Time between frames in milliseconds   ***/

var AniImage = new Array();
var AniFrame = new Array();

var BaseHref="images/";
var Sep     = "/";
var Timer   = null;

/***********************************************************
* Function   : ImageError
*
* Parameters : 
*              
* Description : If the image being loaded does not exist then
*               this function will report an error giving the
*               full URL of the image we are trying to load.
*              
***********************************************************/
function ImageError()
{
	alert("animate.js has detected an error\nImage not found\n" + this.src);
}
/***********************************************************
* Function   : CreateAnimationFrames
*
* Parameters : aniName - the name of the animation.
*              n       - number of frames in animation
*              ext     - the type of image (".GIF", ".JPG")
*              
* Description : Creates an object that can hold the current
*               images for the animation.
*               There must be 1 ".ext" file for every frame
*               of animation  and they must reside in a 
*               the directory "images/name/x.ext". 
*               E.g.
*                 "images/email/0.gif"
*                 "images/email/1.gif"
*                 ....
*                 "images/email/x.gif" //where x=(n-1);
***********************************************************/
function CreateAnimationFrames(aniName, n, ext)
{
	this.num_frames = n;
	for(var i=0 ; i<n ; i++)
	{
		this[i]=new Image();
		this[i].src = BaseHref + aniName + Sep + i + ext;

		this[i].onerror=ImageError;
	}
}
/***********************************************************
* Function   : CreateAnimatedImage
*
* Parameters : imgNname - the name of the image.
*              aniName  - the name of the animation effect
*                         to use with this image
*              
* Description : Creates an object that can hold the current
*               state of the animation for a particular image
* NOTE: imgName must match an image defined in the document
*	  BODY (e.g. <IMG SRC="xxx.ext" NAME="imgName">)
***********************************************************/
function CreateAnimatedImage(imgName, aniName)
{
	if(document.images)
	{
		this.img_name   = imgName;
		this.ani_name   = aniName;
		this.next_on    = null;
		this.next_off   = null;
		this.index      = 0;
		this.target_frame= 0;
		this.state      = "CLOSED";
		this.img	    = null;
	}
	
}
/***********************************************************
* Function   : AnimatedImage
*
* Parameters : imgName - the name of the image.
*              aniName - the name of the animation effect
*                     to use with this image
*              
* Description : Creates an object to hold the current state of
*		    the animation and stores it in the AniImage array.
***********************************************************/
function AnimatedImage(imgName, aniName)
{
	AniImage[ imgName ] = new CreateAnimatedImage( imgName, aniName);

	if(AniFrame[aniName]==null)
		alert("animate.js has detected a possible error\n       --------------------------------\n"
		    + "Error	: AnimationFrames \"" + aniName + "\" not defined\n"
		    + "Function	: AnimatedImage(\"" + imgName + "\",\"" + aniName + "\")");

}
/***********************************************************
* Function   : AnimationFrames
*
* Parameters : aniName - the name of the animation effect
*              n    - number of frames in animation
*              ext  - the type of image (".GIF", ".JPG")
*              
* Description : Creates an object to hold all the frames
*               for an animation and stores it in the AniFrames array.
***********************************************************/
function AnimationFrames(aniName, n, ext)
{
	/*** Only download this animation if we don't already have it ***/
	if(AniFrame[aniName] == null)
		AniFrame[ aniName ]= new CreateAnimationFrames(aniName, n, ext);
	else
//		alert(aniName + " already defined");
		;
}
/***********************************************************
* Function   : AnimatedGif AnimatedJpg
*
* Parameters : name - the name of the image.
*              n    - number of frames in animation
*              
* Description : These are a couple of helper functions to
*               help create simple animations.
*
***********************************************************/
function AnimatedGif(name, n)
{
	AnimationFrames(name, n, ".gif");
	AnimatedImage( name, name);
}
function AnimatedJpg(name, n)
{
	AnimationFrames(name, n, ".jpg");
	AnimatedImage( name, name);
}

/*****************************************************************
* Function    : getImage
*
* Parameters : n - the name of the image to find
*		   d - the (window/layer) document
*
* Description : In ie - just get the doucument.image.
*		    In NS, if there are layers we recursively
*		    search them for the image.
*
*****************************************************************/
function getImage(n, d) 
{
	var img = d.images[n];
	if(!img && d.layers)  
		for(var i=0 ; !img && i<d.layers.length ; i++) 
			img=getImage(n,d.layers[i].document); 
	return img;
}

/*****************************************************************
* Function    : startAnimation
*
* Description : Set a timeout which will call the animate routine
*               and start the animation running
*****************************************************************/
function startAnimation()
{
	if(!AnimationRunning)
		Animate();
}
/*****************************************************************
*
* Function   : turn_on
*
* Parameters : ingName - string containing the name of the
*                        image to start animating.
*		   aniName - optional, animation to use to open.
*
* Description: Checks that the imgName is in a valid state to
*              start "OPENING". If it is it sets the state to
*              "OPENING" and calls startAnimation.             
*
*****************************************************************/
function turn_on(imgName, aniName)
{
	if(!ErrorCheck("turn_on", imgName, aniName))
	{
		var b=AniImage[ imgName ];

		if(b.state == "CLOSED" )
		{
			b.state = "OPENING";
			if(aniName)
				b.ani_name=aniName;
			startAnimation();
		}
		else if ( b.state == "OPEN_CLOSE"
			||  b.state == "CLOSING" 
			||  b.state == "CLOSE_OPEN") 
		{
			if(!aniName || b.ani_name==aniName)
				b.state = "OPENING";
			else
			{
				b.next_on=aniName;
				b.state = "CLOSE_OPEN";
			}
		}
		/*** Special effect, can only happen in forced situations ***/
		/*** Hopefully this is described in the manual ***/
		else if( b.state == "OPENING"
			|| b.state == "OPEN")
		{
			if(aniName && b.ani_name != aniName)
			{
				b.ani_name=aniName;
				b.index=0;
				b.state="OPENING";
				startAnimation();
			}
		}
	}
}
/*****************************************************************
*
* Function   : turn_off
*
* Parameters : imgName - string containing the name of the
*                        image to start reverse animating.
*
* Description: Checks that the imgName is in a valid state to
*              start "CLOSING". If it is it sets the state to
*              "CLOSING" and calls startAnimation.             
*
*****************************************************************/
function turn_off(imgName, aniName)
{
	if(!ErrorCheck("turn_off", imgName, aniName))
	{
		var b=AniImage[ imgName ];

		if( b.state == "OPEN")		
		{
			if(aniName)
			{
				b.ani_name=aniName;
				b.index=AniFrame[aniName].num_frames-1;
			}
			b.state = "CLOSING";
			startAnimation();
		}
		else if(b.state == "CLOSE_OPEN")
		{
			b.next_off=null;
			b.state="CLOSING"
		}
		else if( b.state == "OPENING" )
		{
			b.next_off = aniName;
			b.state = "OPEN_CLOSE";
		}
	}
}
/*******************************************************************
*
* Function    : Animate
*
* Description : Each animation object has a state.
*               The states normally go as follows
*                   CLOSED->OPENING->OPEN
*                   OPEN->CLOSING->CLOSED.
*               When a turn_on() event is received, an image in the
*               CLOSED state is switched to the OPENING state until OPEN
*               is reached. When the turn_off() event is received an image
*               in the OPEN state is switched to the CLOSING state until
*               the CLOSED state is reached. 
*
*               The special cases are what happens when we get turn_off() when
*               in the middle of opening. In this case the path is :-
*               CLOSED->OPENING->OPEN_CLOSE->CLOSING->CLOSED.
*               in this way the image will fully "open" before it starts 
*               closing. This can be changed by always setting the state
*               to "CLOSING" when the turn_off() event is received.
*
*               If the button is "CLOSING" and the turn_on() event is
*               received and the new open animation is null or the same
*               then the state is set back to "OPENING and the
*               button will start opening again immediately.
*                 Otherwise the state is set to CLOSE_OPEN so the image
*               will get to the CLOSED state and start opening with the
*               new animation.
*
*******************************************************************/
function Animate()
{	
	AnimationRunning = false; /*** Are there more frames that need displaying? ***/

	for(var i in AniImage)
	{
		var b=AniImage[i];
		var a=AniFrame[b.ani_name];

		if(b.state == "OPENING")
		{
			/*** Increment the frame index - display the next frame ***/
			/*** when fully open, set state to "OPEN"               ***/
			if(++b.index < a.num_frames)
			{
				b.img.src=a[b.index].src;
				AnimationRunning = true;
			}
			else
			{
				b.index=a.num_frames-1;
				b.state = "OPEN";
			}
		}
		else if(b.state == "OPEN_CLOSE")
		{
			/*** Increment the frame index - display the next frame ***/
			/*** when fully open, set state to "CLOSING"            ***/
			if(++b.index < a.num_frames)
			{
				b.img.src=a[b.index].src;
			}
			else
			{
				if(b.next_off)
				{
					b.ani_name=b.next_off;
					a=AniFrame[b.ani_name];
					b.next_off=null;
				}
				b.index=a.num_frames-1;
				b.state = "CLOSING";
			}
			AnimationRunning = true;
		}
		else if(b.state == "CLOSING")
		{
			/*** Decrement the frame index - display the next frame ***/
			/*** when fully closed, set state to "CLOSED"           ***/
			if(--b.index >= 0)
			{
				b.img.src=a[b.index].src;
				AnimationRunning = true;
			}
			else
			{
				b.index=0;
				b.state = "CLOSED";
			}
		}
		else if(b.state == "CLOSE_OPEN")
		{
			/*** Decrement the frame index - display the next frame ***/
			/*** when fully closed, set state to "OPENING"           ***/
			if(--b.index >= 0)
			{
				b.img.src=a[b.index].src;
			}
			else
			{
				b.index=0;
				b.ani_name=b.next_on;
				b.state = "OPENING";
			}
			AnimationRunning = true;
		}
		else if(b.state == "ROTATE_UP")
		{
			/*** Increment the frame index - display the next frame ***/
			/*** when target reached, set state to "CLOSED"        ***/
			if(b.index != b.target_frame)
			{
				if(++b.index == a.num_frames)
					b.index = 0;
				b.img.src=a[b.index].src;
				AnimationRunning = true;
			}
			else
				b.state = "CLOSED";
		}
		else if(b.state == "ROTATE_DOWN")
		{
			/*** Decrement the frame index - display the next frame ***/
			/*** when target reached, set state to "CLOSED"        ***/
			if(b.index != b.target_frame)
			{
				if(--b.index < 0)
					b.index = a.num_frames-1;
				b.img.src=a[b.index].src;
				AnimationRunning = true;
			}
			else
				b.state = "CLOSED";
		}
	}
	/*** Check to see if we need to animate any more frames. ***/
	if(AnimationRunning)
	{
		if(!Timer)
			Timer=setInterval("Animate()",FrameInterval);
	}
	else
	{
		clearInterval(Timer);
		Timer=null;
	}
}



/***********************************************************
* Function   : ErrorCheck
*
* Parameters : funcName - the name of the function that called this one
*              imgName  - The name of the image being animated
*              aniName  - (optional) the animation being used.
*              
* Description : This function checks that all the required
*               objects that make up an animated onMouseOver
*               have been defined. It will report any errors
*               detected. This function will also search for
*		    the corresponding document image by calling getImage().
*              
***********************************************************/
function ErrorCheck(funcName, imgName, aniName)
{
	var err_str="";

	if(AniImage[imgName]==null)
		err_str += "Error	: AnimatedImage \"" + imgName + "\" not defined\n";
	else
	{
		var b=AniImage[imgName];
		if(b.img == null)
			b.img=getImage(imgName, document);

		if(b.img==null)
			err_str += "Error	: Document <IMG NAME=\"" + imgName + "\"> not defined\n";

		/*** Check the AnimationFrames(b.ani_name, n, ext) has been defined ***/
		if(AniFrame[b.ani_name]==null)
			err_str += "Error	: AnimationFrames \"" + b.ani_name + "\" not defined\n";

	}

	if(aniName)
		/*** Check the AnimationFrames(aniName, n, ext) has been defined ***/
		if(AniFrame[aniName]==null)
			err_str += "Error	: AnimationFrames \"" + aniName + "\" not defined\n";

	if(err_str)
	{
		var extra = aniName ? ("\",\"" + aniName + "\")") : ("\")");
		alert("animate.js has detected an error\n       --------------------------------\n"
		    + err_str
		    + "Function	: " + funcName + "(\"" + imgName + extra);
	
		return true;
	}

	return false;
}
/*****************************************************************
*
* Function   : animate_to
*
* Parameters : imgName - string containing the name of the
*                        image to start animating.
*              frameNo - the target frame
*
* Description: Determines the shortest animation path to the
*		   target frame. sets the state and calls startAnimation.             
*
*****************************************************************/
function animate_to(frameNo, imgName)
{
	if(!ErrorCheck("animate_to", imgName) )
	{
		var b = AniImage[ imgName  ];
		var a = AniFrame[ b.ani_name];
		var s = frameNo - b.index;

		b.target_frame = frameNo;

		if(Math.abs(s) > (a.num_frames/2))
		{
			if(s < 0)
				b.state = "ROTATE_UP";
			else
				b.state = "ROTATE_DOWN";
		}
		else
		{
			if(s > 0)
				b.state = "ROTATE_UP";
			else
				b.state = "ROTATE_DOWN";
		}
		startAnimation();
	}
}
/*****************************************************************
*
* Function   : animate_upto
*
* Parameters : imgName - string containing the name of the
*                        image to start animating.
*              frameNo - the target frame
*
* Description: Sets the state to ROTATE_UP calls startAnimation.             
*
*****************************************************************/
function animate_upto(frameNo, imgName)
{
	if(!ErrorCheck("animate_upto", imgName) )
	{
		var b=AniImage[ imgName ];

		b.target_frame = frameNo;
		b.state       = "ROTATE_UP";

		startAnimation();
	}
}
/*****************************************************************
*
* Function   : animate_downto
*
* Parameters : imgName - string containing the name of the
*                        image to start animating.
*              frameNo - the target frame
*
* Description: Sets the state to ROTATE_DOWN calls startAnimation.             
*
*****************************************************************/
function animate_downto(frameNo, imgName)
{
	if(!ErrorCheck("animate_downto", imgName) )
	{
		var b=AniImage[ imgName ];

		b.target_frame = frameNo;
		b.state       = "ROTATE_DOWN";
		startAnimation();
	}
}



#include <iostream>
#include <cstdio>
#include <cstdlib>
#include <string>
#include <cstring>
#include <exception>

#include "SDL.h"
#include "SDL2_gfxPrimitives.h"
#include "SDL_ttf.h"

#include "pong.hpp"

using namespace std;

int main(int argc, char* argv[]) {
	if(argc==2) {
		if( strcmp(argv[1],"-v")==0 || strcmp(argv[1],"--version")==0 ) {
			cout << "PONG version 0.0.1 by sebbu" << endl;
		}
	}
	int res=0;
	res=SDL_Init(SDL_INIT_VIDEO|SDL_INIT_TIMER|SDL_INIT_EVENTS);
	if(res!=0) {
		fprintf(stderr, "\nUnable to initialize SDL:  %s\n", SDL_GetError() );
        return 1;
	}
	atexit(SDL_Quit);
	//SDL initialized
	if(TTF_Init()==-1) {
		fprintf(stderr, "TTF_Init: %s\n", TTF_GetError());
		exit(2);
	}
	TTF_Font *font;
	font=TTF_OpenFont("arial.ttf", 16);
	if(!font) {
		fprintf(stderr, "TTF_OpenFont: %s\n", TTF_GetError());
		// handle error
		exit(2);
	}
	//SDL_ttf initialized
	SDL_Window *window;
	const int width=640;
	const int height=480;
	window = SDL_CreateWindow(
		"PONG",						// window title
		SDL_WINDOWPOS_UNDEFINED,	// initial x position
		SDL_WINDOWPOS_UNDEFINED,	// initial y position
		width,						// width, in pixels
		height,						// height, in pixels
		SDL_WINDOW_OPENGL			// flags - see below
	);
	if (window == NULL) {
		// In the event that the window could not be made...
		fprintf(stderr, "Could not create window: %s\n", SDL_GetError());
		return 1;
	}
	SDL_Renderer *renderer = NULL;
	renderer = SDL_CreateRenderer(window, -1, SDL_RENDERER_ACCELERATED);
	
	unsigned int fps=60;//frame per second
	unsigned int t1=0;//time start loop
	unsigned int t2=0;//time end loop
	
	int x=width/2;//x position ball
	int y=height/2;//y position ball
	int size=5;//size ball
	const int bm=2;//ball move (in px)
	
	const int pm=8;//player move (in px)
	const int ps=20;//player size from screen border
	const int pw=10;//player width
	
	int p1y=y;//player 1 y position
	int p1s=size*4;//player 1 size
	int p1l=0;//player 1 lives
	char p1t[21];
	
	int p2y=y;//player 2 y position
	int p2s=size*4;//player 2 size
	int p2l=0;//player 2 lives
	char p2t[21];
	
	SDL_Rect dst;
	dst.x=0;
	dst.y=0;
	
	bool cond=true;//loop
	
	enum direction {
		UP=0,
		UPRIGHT=45,
		RIGHT=90,
		DOWNRIGHT=135,
		DOWN=180,
		DOWNLEFT=225,
		LEFT=270,
		UPLEFT=315
	};
	int dir=UPRIGHT;//degree angle of ball, top is 0, right 90, bottom 180, left 270
	
	SDL_Event e;
	SDL_Surface *text_surface1;
	SDL_Surface *text_surface2;
	SDL_Texture *tex1;
	SDL_Texture *tex2;
	
	while (cond) {
		t1=SDL_GetTicks();
		if (SDL_PollEvent(&e)) {
			if (e.type == SDL_QUIT) {
				cond=false;
				continue;
			}
			else if(e.type == SDL_KEYDOWN) {
				switch(e.key.keysym.scancode) {
					case SDL_SCANCODE_ESCAPE:
						cond=false;
						continue;
					case SDL_SCANCODE_W: //player 1 up
						p1y-=pm;
						break;
					case SDL_SCANCODE_S: //player 1 down
						p1y+=pm;
						break;
					case SDL_SCANCODE_UP: // player 2 up
						p2y-=pm;
						break;
					case SDL_SCANCODE_DOWN: //player 2 down
						p2y+=pm;
						break;
					default:
						break;
				}
			}
		}
		//fix positions
		if(p1y<p1s/2) p1y=p1s/2;
		if(p2y<p2s/2) p2y=p2s/2;
		if(p1y>height-p1s/2) p1y=height-p1s/2;
		if(p2y>height-p2s/2) p2y=height-p2s/2;
		//fix sizes
		if(size<1) size=1;
		if(p1s<1) p1s=1;
		if(p2s<1) p2s=1;
		//move ball
		switch(dir) {
			case UPRIGHT:
				x+=bm;
				y-=bm;
				break;
			case DOWNRIGHT:
				x+=bm;
				y+=bm;
				break;
			case DOWNLEFT:
				x-=bm;
				y+=bm;
				break;
			case UPLEFT:
				x-=bm;
				y-=bm;
				break;
		}
		//players collisions
		if( x+size <= ps+pw && ( ( y+size >= p1y-p1s && y+size <= p1y+p1s ) || ( y-size <= p1y+p1s && y-size >= p1y-p1s) ) ) { //player 1 (left)
			if(dir==UPLEFT) dir=UPRIGHT;
			if(dir==DOWNLEFT) dir=DOWNRIGHT;
			//dir-=90;
			//dir=(180-dir)%360;
			//dir+=90;
		}
		if( x+size >= width-ps-pw && ( ( y+size >= p2y-p2s && y+size <= p2y+p2s ) || ( y-size <= p2y+p2s && y-size >= p2y-p2s) ) ) { //player 2 (right)
			if(dir==UPRIGHT) dir=UPLEFT;
			if(dir==DOWNRIGHT) dir=DOWNLEFT;
			//dir-=90;
			//dir=(180-dir)%360;
			//dir+=90;
		}
		//borders collisions
		if( x+size >= width ) { //RIGHT
			if(dir==UPRIGHT) dir=UPLEFT;
			if(dir==DOWNRIGHT) dir=DOWNLEFT;
			//dir=(dir+180)%360;
			x=width/2;
			y=height/2;
			p2l+=1;
		}
		if( y+size > height ) { //DOWN
			if(dir==DOWNRIGHT) dir=UPRIGHT;
			if(dir==DOWNLEFT) dir=UPLEFT;
			//dir=(180-dir)%360;
		}
		if( x-size < 0 ) { //LEFT
			if(dir==UPLEFT) dir=UPRIGHT;
			if(dir==DOWNLEFT) dir=DOWNRIGHT;
			//dir=(dir+180)%360;
			x=width/2;
			y=height/2;
			p1l+=1;
		}
		if( y-size < 0 ) { //UP
			if(dir==UPRIGHT) dir=DOWNRIGHT;
			if(dir==UPLEFT) dir=DOWNLEFT;
			//dir=(180-dir)%360;
		}
		//if(dir<0) dir+=360;
		//clear
		SDL_SetRenderDrawColor(renderer, 0, 0, 0, 255);
		SDL_RenderClear(renderer);
		//score
		SDL_Color color={0,0,255,255};
		sprintf(p1t, "Player 1: %d", p1l);
		sprintf(p2t, "Player 2: %d", p2l);
		if(!(text_surface1=TTF_RenderText_Solid(font, p1t, color))) {
			//handle error here, perhaps print TTF_GetError at least
			fprintf(stderr, "TTF_RenderText_Solid: %s\n", TTF_GetError());
			exit(2);
		}
		if(!(text_surface2=TTF_RenderText_Solid(font, p2t, color))) {
			//handle error here, perhaps print TTF_GetError at least
			fprintf(stderr, "TTF_RenderText_Solid: %s\n", TTF_GetError());
			exit(2);
		}
		if(true) {
			tex1=SDL_CreateTextureFromSurface(renderer, text_surface1);
			SDL_FreeSurface(text_surface1);
			dst.w=text_surface1->w;
			dst.h=text_surface1->h;
			dst.x=5;
			dst.y=5;
			SDL_RenderCopy(renderer, tex1, NULL, &dst);
			SDL_DestroyTexture(tex1);
			tex2=SDL_CreateTextureFromSurface(renderer, text_surface2);
			SDL_FreeSurface(text_surface2);
			dst.x=width-dst.w-5;
			SDL_RenderCopy(renderer, tex2, NULL, &dst);
			SDL_DestroyTexture(tex2);
		}
		//ball
		filledCircleRGBA(renderer, x, y, size, 255, 0, 0, 255);
		//left player
		boxRGBA(renderer, ps, p1y-p1s, ps+pw, p1y+p1s, 0, 255, 0, 255);
		//right player
		boxRGBA(renderer, width-ps-pw, p2y-p2s, width-ps, p2y+p2s, 0, 255, 0, 255);
		//render
		SDL_RenderPresent(renderer);
		t2=SDL_GetTicks();
		if( (t2-t1) < (1000/fps)) {
			SDL_Delay( (1000/fps) - (t2-t1) );
		}
	}
	// Close and destroy the window
	SDL_DestroyWindow(window);
	TTF_CloseFont(font);
	TTF_Quit();
	return 0;
}

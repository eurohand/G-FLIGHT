<!DOCTYPE HTML>
<html>
<head>
<title>G FLIGHT - Data Structure Project</title>
</head>
<body>
<canvas id = "canvas" width = "1500" height = "700"></canvas>
<style>
  @import url('https://fonts.googleapis.com/css?family=VT323&display=swap'); // VT323 font
</style>
<script>
	


	//#constant------------------------------------------------------------------------------------------------------------------
	
	const canvas = document.getElementById("canvas");
	const ctx = canvas.getContext("2d");
	const width = canvas.width;
	const height = canvas.height;
	const BODY = document.querySelector('body');
	const SHIP_SIZE = 30;
	const SHOOT_SIZE = 4;
	const ENEMY_SIZE = 40;
	const PORTION_SIZE = 6;
	const IMG_SIZE = 75;
	const START_Y = 275;
	const STORE_Y = 350;
	const RANK_Y = 425;
	const TABLE_SIZE = 13;
	const FONT_NAME = 'VT323';
	const IMG_SRC = 'https://lh3.googleusercontent.com/Zupg6_TMI0uRGRYQEVJ9ULDslHDG4TSIf1fmQ4zeIoL-cUbMe_lOoYjJoih5LjIARw';
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#basic---------------------------------------------------------------------------------------------------------------------

	function textSetting(align, baseline, font){
		ctx.textAlign = align;
		ctx.textBaseline = baseline;
		ctx.font = font;
	}
	
	
	function fontText(px, intext, x, y){
		ctx.fillStyle = "white"
		ctx.font = px + `px "${FONT_NAME}"`;
		ctx.fillText(intext, x, y);
	}
	
	function drawBorder() {
		ctx.fillStyle = "Gray";
		ctx.fillRect(0, 0, width, 10);
		ctx.fillRect(0, height-10, width, 10);
		ctx.fillRect(0, 0, 10, height);
		ctx.fillRect(width-10, 0, 10, height);
	}
	
	function gameOver() {
		clearInterval(intervalId);
		ctx.fillStyle =  "White";
		textSetting("center", "middle", "30px Courier");
		ctx.fillText("Game Over", width/2, height/2);
		pointCheck();
		scoreCheck();
		let name = prompt("TYPE YOUR NAME: ");
		uploadRank(name);
	}
	
	function pointCheck(){
		let currentPoint = parseInt(localStorage.getItem('Credit'));
		if(currentPoint !== NaN){
			localStorage.setItem('Credit', parseInt(score/1000));
		}else{
			currentPoint += parseInt(score/1000);
			localStorage.setItem('Credit', currentPoint);
		}
	}
	
	
	function scoreCheck(){
		let currentScore = parseInt(localStorage.getItem('HighScore'));
		if(currentScore !== NaN){
			localStorage.setItem('HighScore', parseInt(score));
		}else{
			if(currentScore < score){
				localStorage.setItem('HighScore', score);
			}
		}
	}
	
	function shotCheck(){
		let currentShot = parseInt(localStorage.getItem('Shot'));
		if(currentShot !== NaN){
			localStorage.setItem('Shot', parseInt(shipShootStatus));
		}else{
			shipShootStatus = currentShot;
		}
	}
	
	function skillCheck(){
		let currentSkill = parseInt(localStorage.getItem('Skill'));
		if(currentSkill !== NaN){
			localStorage.setItem('Skill', parseInt(shipSkillStatus));
		}else{
			shipSkillStatus = currentSkill;
		}
	}

	
	function drawScore() {
		ctx.textBaseline = "top";
		ctx.textAlign = "left";
		ctx.font = "15px Courier";
		ctx.fillStyle = "Gray";
		ctx.fillText("W: move up, A: move left, S: move down, D: move right, click: shoot, space: ult, esc: home", 15, 15);
		ctx.font = "20px Courier";
		ctx.fillStyle = "White";	
		ctx.fillText("Score : " + score, 15, 35);
		ctx.fillStyle = "Gray"
		ctx.fillRect(15, 60, 110, 40);
		ctx.fillStyle = "Red";
		ctx.fillRect(20, 65, ship.hp, 10);
		ctx.fillStyle = "Blue";
		ctx.fillRect(20, 85, ship.mp, 10);
	};
	
	
	function circle(x, y, r){
		ctx.beginPath();
		ctx.arc(x, y, r, 0, Math.PI*2, false);
		ctx.closePath();
	}
	
	let random = (number) => Math.floor(Math.random() * number);
	let plusminus = () => (random(2) === 0)? 1 : -1;
	let sin = (a) => Math.sin(Math.PI/180 * a);
	let cos = (a) => Math.cos(Math.PI/180 * a);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#main----------------------------------------------------------------------------------------------------------------------
	
	function readyGame() {
		ctx.fillRect(0, 0, width, height);
		dots.forEach((dot) => {dot.draw();});
		drawBorder();
		drawLoading();
	}
	
	
	function drawLoading(){
		textSetting("center", "middle", "30px Courier");
		ctx.drawImage(gachonImg, width-100, 25, IMG_SIZE, IMG_SIZE);	
		ctx.fillStyle = "White";
		
		fontText(20, "2019 DATA STRUCTURE PROJECT", 125, 675);
		fontText(20, "CONTACT: eurohand@gc.gachon.ac.kr", 1350, 675);
		fontText(150, "G-flight", width*0.5, height*0.2);
		
		fontText(50, "INSERT COIN", 775, START_Y);
		fontText(50, "GAME STORE", 775, STORE_Y);
		fontText(50, "RANKING", 775, RANK_Y);
		
		fontText(20, "201635839 RHEE EURO", 750, 550);
		fontText(20, "201532749 LEE JAEYOON", 750, 575);
		fontText(20, "201635827 OH TAEHO", 750, 600);
		fontText(20, "201635824 YEO JUNKU", 750, 625);
		updateCredit();
		updateHighScore();
		textSetting("left", "middle", "30px Courier");
		fontText(30, `CURRENT CREDIT: ${point}`, 30, 40);
		fontText(30, `TODAY's HIGH SCORE: ${highScore}`, 30, 80);
		arrow.draw();
		ctx.fillStyle = "Black";
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#hash----------------------------------------------------------------------------------------------------------------------
	
	function hash(name){
		let key = 0;
		for(let i=0 ; i<name.length ; i++){
			key += name.charCodeAt(i);
		}
		return key%TABLE_SIZE;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#arrow---------------------------------------------------------------------------------------------------------------------
	let Arrow = function(){
		this.y = START_Y;
		this.r = 15;
		this.updown = true;
	}
	
	Arrow.prototype.draw = function(){
		ctx.strokeStyle = "White";
		ctx.beginPath();
		ctx.moveTo(650, this.y);
		ctx.lineTo(620, this.y + this.r);
		ctx.lineTo(620, this.y - this.r);
		ctx.closePath();
		ctx.fill();
		if(this.r >= 15 || this.r <= 0){
			this.updown = !this.updown;
		}
		if(this.updown){
			this.r += 2;
		}else{
			this.r -= 2;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#quadtree------------------------------------------------------------------------------------------------------------------
	
	let Rectangle = function(x, y, w, h){
		this.x = x;
		this.y = y;
		this.w = w;
		this.h = h;
	}
	
	Rectangle.prototype.contains = function(point){
		return (point.x >= this.x - this.w
				&& point.x <= this.x + this.w 
				&& point.y >= this.y - this.h
				&& point.y <= this.y + this.h);
	}
	
	let QuadTree = function (boundary, n){
		this.boundary = boundary;
		this.capacity = n;
		this.points = [];
		this.divided = false;
	}
	
	QuadTree.prototype.subdivide = function(){
		let x = this.boundary.x;
		let y = this.boundary.y;
		let w = this.boundary.w;
		let h = this.boundary.h;
		
		let ne = new Rectangle(x + w/2, y - h/2, w/2, h/2);
		this.northeast = new QuadTree(ne, this.capacity);
		let nw = new Rectangle(x - w/2, y - h/2, w/2, h/2);
		this.northwest = new QuadTree(nw, this.capacity);
		let se = new Rectangle(x + w/2, y + h/2, w/2, h/2);
		this.southeast = new QuadTree(se, this.capacity);
		let sw = new Rectangle(x - w/2, y + h/2, w/2, h/2);
		this.southwest = new QuadTree(sw, this.capacity);
	}
	
	QuadTree.prototype.insert = function(point){
		if(!this.boundary.contains(point)){
			return;
		}
		
		if(this.points.length < this.capacity){
			this.points.push(point);
		} else{
			if(!this.divided){
				this.subdivide();
				this.divided = true;
			}
			this.northeast.insert(point);
			this.northwest.insert(point);
			this.southeast.insert(point);
			this.southwest.insert(point);	
		}
	}
	
	QuadTree.prototype.show = function(){
		ctx.lineWidth = 1;
		ctx.strokeStyle = "lime";
		ctx.strokeRect(this.boundary.x-this.boundary.w, this.boundary.y-this.boundary.h, this.boundary.w*2, this.boundary.h*2);
		if(this.divided){
			this.northeast.show();
			this.northwest.show();
			this.southeast.show();
			this.southwest.show();
		}
	}
	
	QuadTree.prototype.refresh = function(){
		shipShoots.forEach((shoot) => this.insert(shoot));
		enemies.forEach((enemy) => this.insert(enemy));
		portions.forEach((portion) => this.insert(portion));
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#ship---------------------------------------------------------------------------------------------------------------------
	
	let Ship = function (x, y){
		this.x = x;
		this.y = y;
		
		this.xSpeed = 0;
		this.ySpeed = 0;
		
		this.r = SHIP_SIZE / 2;
		this.sin = -1;
		this.cos = 0;
		
		this.hp = 100;
		this.mp = 100;
		
		this.shootStatus = 0;
		this.skillStatus = 0;
		
		this.wavePoint = 0;
		this.waveControl = true;
	}
	
	Ship.prototype.move = function() {
		this.x += this.xSpeed;
		this.y += this.ySpeed;
		if(this.x < 20){
			this.x = 20;
		}
		if(this.x > width-20){
			this.x = width-20;
		}
		if(this.y < 40) {
			this.y = 40;
		}
		if(this.y > height-40){
			this.y = height-40;
		}
	}
	
	Ship.prototype.draw = function() {
		ctx.fillStyle = "White";
		ctx.strokeStyle = "White";
		ctx.lineWidth = this.r / 3;
        ctx.beginPath();
        ctx.moveTo( // nose of the ship
            this.x + 4 / 3 * this.r * this.cos,
            this.y - 4 / 3 * this.r * this.sin
        );
        ctx.lineTo( // rear left
            this.x - this.r * (2 / 3 * this.cos + this.sin),
            this.y + this.r * (2 / 3 * this.sin - this.cos)
        );
        ctx.lineTo( // rear right
            this.x - this.r * (2 / 3 * this.cos - this.sin),
            this.y + this.r * (2 / 3 * this.sin + this.cos)
        );
        ctx.closePath();
        ctx.stroke();
	}
	
	Ship.prototype.shoot = function() {
		shotCheck();
		if(this.shootStatus === 0){
			this.normalShot();
		}else if(this.shootStatus === 1){
			this.doubleShot();
		}else if(this.shootStatus === 2){
			this.tripleShot();
		}else if(this.shootStatus === 3){
			this.waveShot();
		}else if(this.shootStatus === 4){
			this.spreadShot();
		}
	}
	
	Ship.prototype.setDirection = function(direction) {
		if(direction === "up"){
			this.ySpeed = -4;
		}else if(direction === "down"){
			this.ySpeed = 4;
		}else if(direction === "left"){
			this.xSpeed = -4;
		}else if(direction === "right"){
			this.xSpeed = 4;
		}else if (direction === "space"){
			if(this.mp >=100){
				this.skill();
				this.mp -=100;
			}
		}else if(direction === "quadtree"){
			if(quadtree){
				quadtree = false;
			}else{
				quadtree = true;
			}	
		}
	}
	
	
	Ship.prototype.checkCollision = function() {
		this.enemyCollision();
		this.shootCollision();
		this.portionCollision();
		if(this.hp <= 0){
			this.hp = 0;
			gameOver();
		}
	}
	
	Ship.prototype.enemyCollision = function() {
		let left = this.x - this.r;
		let right = this.x + this.r;
		let up = this.y - this.r;
		let down = this.y + this.r;
		let x = this.x;
		let y = this.y;
		
		enemies.forEach((enemy) => {
			if((left < enemy.x + enemy.w/2 &&
			right > enemy.x - enemy.w/2 &&
			up < enemy.y + enemy.h/2 &&
			down > enemy.y - enemy.h/2 &&
			enemy.species === 0)
			|| (Math.sqrt((enemy.x-x)*(enemy.x-x) + (enemy.y-y)*(enemy.y-y)) < ENEMY_SIZE && enemy.species === 1)){
				this.hp -= 100;
			}
		});
	}
	
	Ship.prototype.shootCollision = function() {
		let left = this.x - this.r;
		let right = this.x + this.r;
		let up = this.y - this.r;
		let down = this.y + this.r;
		
		shipShoots.forEach((shoot) => {
			if(shoot.x > left && shoot.x < right && shoot.y > up && shoot.y < down && !shoot.my){
				this.hp -= 25;
				shipShoots.splice(shipShoots.indexOf(shoot), 1);
			}
		});
	}
	
	Ship.prototype.portionCollision = function(){
		let left = this.x - this.r;
		let right = this.x + this.r;
		let up = this.y - this.r;
		let down = this.y + this.r;
		
		portions.forEach((portion) => {
			if(portion.x > left && portion.x < right && portion.y > up && portion.y < down){
				this.hpPlus(25);
				booms.push(new Boom(this.x, this.y, "portion"));
				portions.splice(portions.indexOf(portion), 1);
				
			}
		});
	}
	
	Ship.prototype.skill = function() {
		skillCheck();
		if(this.skillStatus === 0){
			this.normalSkill();
		}else if(this.skillStatus === 1){
			this.circleSkill();
		}else if(this.skillStatus === 2){
			this.lineSkill();
		}else if(this.skillStatus === 3){
			for(var i=0 ; i<120 ; i++){
			shipShoots.push(new Shoot(this.x, this.y, 50*Math.cos(Math.PI/180*i*3), -50*Math.sin(Math.PI/180*i*3), true));
			}
		}else if(this.skillStatus === 4){
			this.mineSkill();
		}
	}
	
	Ship.prototype.mpPlus = function(amount){
		this.mp += amount;
		if(this.mp >= 100){
			this.mp = 100;
		}
	}
	
	Ship.prototype.hpPlus = function(amount){
		this.hp += amount;
		if(this.hp >= 100){
			this.hp = 100;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#shipshot-----------------------------------------------------------------------------------------------------------------
	
	
	Ship.prototype.normalShot = function(){
		shipShoots.push(new Shoot(this.x, this.y, 50*this.cos, -50*this.sin, true));
	}
	
	Ship.prototype.doubleShot = function(){
		shipShoots.push(new Shoot(this.x - this.r * (2 / 3 * this.cos + this.sin),
			this.y + this.r * (2 / 3 * this.sin - this.cos), 50*this.cos, -50*this.sin, true));
		shipShoots.push(new Shoot(this.x - this.r * (2 / 3 * this.cos - this.sin),
			this.y + this.r * (2 / 3 * this.sin + this.cos), 50*this.cos, -50*this.sin, true));
	}
	
	
	Ship.prototype.tripleShot = function(){
		shipShoots.push(new Shoot(this.x, this.y, 50*this.cos, -50*this.sin, true));
		shipShoots.push(new Shoot(this.x - this.r * (2 / 3 * this.cos + this.sin),
			this.y + this.r * (2 / 3 * this.sin - this.cos), 50*this.cos, -50*this.sin, true));
		shipShoots.push(new Shoot(this.x - this.r * (2 / 3 * this.cos - this.sin),
			this.y + this.r * (2 / 3 * this.sin + this.cos), 50*this.cos, -50*this.sin, true));
	}
	
	Ship.prototype.waveShot = function(){
		let acos = this.cos * cos(this.wavePoint) - this.sin * sin(this.wavePoint);
		let asin = this.sin * cos(this.wavePoint) + this.cos * sin(this.wavePoint);
		shipShoots.push(new Shoot(this.x, this.y, 50*acos, -50*asin, true));
		
		if(this.waveControl){
			this.wavePoint+=10;
		}else{
			this.wavePoint-=10;
		}
		
		if(this.wavePoint === -30 || this.wavePoint === 30){
			this.waveControl = !this.waveControl;
		}
	}
	
	Ship.prototype.spreadShot = function(){
		let acos = this.cos * cos(20) - this.sin * sin(20);
		let asin = this.sin * cos(20) + this.cos * sin(20);
		let bcos = this.cos * cos(-20) - this.sin * sin(-20);
		let bsin = this.sin * cos(-20) + this.cos * sin(-20);
		shipShoots.push(new Shoot(this.x, this.y, 50*this.cos, -50*this.sin, true));
		shipShoots.push(new Shoot(this.x, this.y, 50*acos, -50*asin, true));
		shipShoots.push(new Shoot(this.x, this.y, 50*bcos, -50*bsin, true));
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#shipskill----------------------------------------------------------------------------------------------------------------
	
	
	Ship.prototype.normalSkill = function(){
		for(let i=0 ; i<30 ; i++){
			shipShoots.push(new Shoot(this.x, this.y, 50*Math.cos(Math.PI/180*i*12), -50*Math.sin(Math.PI/180*i*12), true));
		}
	}
	
	
	
	Ship.prototype.circleSkill = function(){
		for(let i=0 ; i<120 ; i++){
			shipShoots.push(new Shoot(this.x, this.y, 50*Math.cos(Math.PI/180*i*3), -50*Math.sin(Math.PI/180*i*3), true));
		}
	}
	
	
	Ship.prototype.lineSkill = function(){
		let temp = 1;
		for(let i=0 ; i<width ; i+=10){
			if(i > this.x){
				temp = 1;
			}else{
				temp = -1;
			}
			shipShoots.push(new Shoot(i, this.y, temp*50, 0, true));
		}
		for(let i=0 ; i<height ; i+=10){
			if(i > this.y){
				temp = 1;
			}else{
				temp = -1;
			}
			shipShoots.push(new Shoot(this.x, i, 0, temp*50, true));
		}
	}
	
	
	Ship.prototype.mineSkill = function(){
		for(let i=0 ; i<150 ; i++){
			shipShoots.push(new Shoot(random(width), random(height), plusminus()*(random(3)+10), plusminus()*(random(3)+10), true));
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#shoot---------------------------------------------------------------------------------------------------------------------
	
	var Shoot = function(x, y, xSpeed, ySpeed, my){
		this.x=x;
		this.y=y;
		this.xSpeed = xSpeed;
		this.ySpeed = ySpeed;
		this.r = SHOOT_SIZE / 2;
		this.use = true;
		this.my = my;
	}
	Shoot.prototype.draw = function(){
		if(this.my){
			ctx.fillStyle = "Cyan";
			ctx.fillRect(this.x-this.r, this.y-this.r, SHOOT_SIZE*1.3, SHOOT_SIZE*1.3);
		}else {
			ctx.fillStyle = "Orangered";
			ctx.fillRect(this.x-this.r, this.y-this.r, SHOOT_SIZE*1.8, SHOOT_SIZE*1.8);
		}
		
	}
	Shoot.prototype.move = function(){
		this.x += this.xSpeed;
		this.y += this.ySpeed;
		if(this.x > width || this.x < 0 || this.y > height || this.y < 0){
			this.use = false;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#enemy---------------------------------------------------------------------------------------------------------------------
	
	let Enemy = function(x, y, w, h, xSpeed, ySpeed, type, species) {
		this.x = x;
		this.y = y;
		this.w = w;
		this.h = h;
		this.xSpeed = xSpeed;
		this.ySpeed = ySpeed;
		this.type = type;
		this.species = species;
		this.count = 0;
		this.use = true;
	}
	
	Enemy.prototype.move = function() {
		this.x += this.xSpeed;
		this.y += this.ySpeed;
		this.count++;
		if(this.species == 1){
			this.count++;
		}
		if(this.count >= 50){
			this.shoot();
			this.count = 0;
		}
	}
	
	Enemy.prototype.draw = function() {
		if(this.species === 0){
			this.drawRocket();
		}else if(this.species === 1){
			this.drawUFO();
		}
	}
		


	
	Enemy.prototype.shoot = function() {
		if(this.species === 0){
			shipShoots.push(new Shoot(this.x, this.y, this.xSpeed * 5, this.ySpeed * 5, false));
		}else if(this.species === 1){
			shipShoots.push(new Shoot(this.x+this.xSpeed*15, this.y+this.ySpeed*15, 5, 5, false));
			shipShoots.push(new Shoot(this.x+this.xSpeed*15, this.y+this.ySpeed*15, -5, 5, false));
			shipShoots.push(new Shoot(this.x+this.xSpeed*15, this.y+this.ySpeed*15, -5, -5, false));
			shipShoots.push(new Shoot(this.x+this.xSpeed*15, this.y+this.ySpeed*15, 5, -5, false));
		}
		
	}
	
	Enemy.prototype.checkCollision = function(i) {
		let left = this.x - this.w/2;
		let right = this.x + this.w/2;
		let up = this.y - this.h/2;
		let down = this.y + this.h/2;
		let x = this.x;
		let y = this.y;
		let species = this.species;
		
		shipShoots.forEach((shoot) => {
			if((shoot.x >= left && shoot.x <= right && shoot.y >= up && shoot.y <= down && shoot.my && species === 0)
			|| (Math.sqrt((shoot.x - x)*(shoot.x - x) + (shoot.y - y)*(shoot.y - y)) < ENEMY_SIZE && shoot.my)){
				enemies.splice(i, 1);
				shipShoots.splice(shipShoots.indexOf(shoot), 1);
				score += 10;
				ship.mpPlus(5);
				booms.push(new Boom(this.x, this.y, "enemy"));
				if(random(100) > 95){
					portions.push(new Portion(this.x, this.y, plusminus()*(2 + random(3)), plusminus()*(2 + random(3))));
				}
				return true;
			}
		});
		return false;
	}
	
	
	function randomEnemy() {
		let type = random(4);
		let species
		if(random(100)>90){
			species = 1;
		}else{
			species = 0;
		}
		fourSide(type, species);
		
	}
	
	function fourSide(type, species){
		let speed;
		let otherSpeed;
		if(species === 0){
			speed = 2;
			otherSpeed = 0;
		}else if (species === 1){
			speed = 3;
			otherSpeed = plusminus() * (random(2)+1);
		}
		
		if(type === 0){
			enemies.push(new Enemy(random(width), 0, ENEMY_SIZE, ENEMY_SIZE*2, otherSpeed, speed, type, species));
		}else if (type === 1){
			enemies.push(new Enemy(random(width), height, ENEMY_SIZE, ENEMY_SIZE*2, otherSpeed, -speed, type, species));
		}else if (type === 2){
			enemies.push(new Enemy(0, random(height), ENEMY_SIZE*2, ENEMY_SIZE, speed, otherSpeed, type, species));
		}else if (type === 3){
			enemies.push(new Enemy(width, random(height), ENEMY_SIZE*2, ENEMY_SIZE, -speed, otherSpeed, type, species));
		}
	}
	
	Enemy.prototype.drawRocket = function(){
		ctx.strokeStyle = "Black";
		let t;
		if(this.type === 0 || this.type === 2){
			t = -ENEMY_SIZE / 8;
		}else if(this.type === 1 || this.type === 3){
			t = ENEMY_SIZE / 8;
		}
		
		if(this.type === 0 || this.type === 1){
			ctx.lineWidth = 1;
			ctx.fillStyle="Pink";
			ctx.beginPath();
			ctx.moveTo(this.x, this.y-(t*8));
			ctx.lineTo(this.x-(t*3), this.y-(t*6));
			ctx.lineTo(this.x+(t*3), this.y-(t*6));
			ctx.lineTo(this.x, this.y-(t*8));
			ctx.closePath();
			ctx.fill();
			ctx.stroke();
			
			ctx.fillStyle="Skyblue";
			ctx.beginPath();
			ctx.moveTo(this.x-(t*3), this.y-(t*6));
			ctx.lineTo(this.x-(t*3), this.y+(t*6));
			ctx.lineTo(this.x+(t*3), this.y+(t*6));
			ctx.lineTo(this.x+(t*3), this.y-(t*6));
			ctx.lineTo(this.x-(t*3), this.y-(t*6));
			ctx.closePath();
			ctx.fill();
			ctx.stroke();
			
			ctx.fillStyle="Green";
			ctx.beginPath();
			ctx.moveTo(this.x-(t*3), this.y);
			ctx.lineTo(this.x-(t*5), this.y+(t*6));
			ctx.lineTo(this.x-(t*3), this.y+(t*6));
			ctx.lineTo(this.x-(t*3), this.y);
			ctx.closePath();
			ctx.fill();
			ctx.stroke();
			
			ctx.beginPath();
			ctx.moveTo(this.x+(t*3), this.y);
			ctx.lineTo(this.x+(t*5), this.y+(t*6));
			ctx.lineTo(this.x+(t*3), this.y+(t*6));
			ctx.lineTo(this.x+(t*3), this.y);
			ctx.closePath();
			ctx.fill();
			ctx.stroke();
		
			ctx.fillStyle="Red";
			ctx.beginPath();
			ctx.lineTo(this.x+(t*3), this.y+(t*6));
			ctx.lineTo(this.x+(t*3), this.y+(t*8));
			ctx.lineTo(this.x+(t*2), this.y+(t*7));
			ctx.lineTo(this.x+t, this.y+(t*8));
			ctx.lineTo(this.x, this.y+(t*7));
			ctx.lineTo(this.x-t, this.y+(t*8));
			ctx.lineTo(this.x-(t*2), this.y+(t*7));
			ctx.lineTo(this.x-(t*3), this.y+(t*8));
			ctx.lineTo(this.x-(t*3), this.y+(t*6));
			ctx.lineTo(this.x+(t*3), this.y+(t*6));
			ctx.closePath();
			ctx.fill();
			ctx.stroke();
			
			ctx.fillStyle="Yellow";
			circle(this.x, this.y, Math.abs(t*2.4));
			ctx.fill();
			ctx.stroke();
			
			ctx.fillStyle="White";
			circle(this.x, this.y, Math.abs(t*1.4));
			ctx.fill();
			ctx.stroke();
		}else if(this.type === 2 || this.type === 3){
			ctx.lineWidth = 1;
			ctx.fillStyle="Pink";
			ctx.beginPath();
			ctx.moveTo(this.x-(t*8), this.y);
			ctx.lineTo(this.x-(t*6), this.y-(t*3));
			ctx.lineTo(this.x-(t*6), this.y+(t*3));
			ctx.lineTo(this.x-(t*8), this.y);
			ctx.closePath();
			ctx.fill();
			ctx.stroke();
		
			ctx.fillStyle="Skyblue";
			ctx.beginPath();
			ctx.moveTo(this.x-(t*6), this.y-(t*3));
			ctx.lineTo(this.x+(t*6), this.y-(t*3));
			ctx.lineTo(this.x+(t*6), this.y+(t*3));
			ctx.lineTo(this.x-(t*6), this.y+(t*3));
			ctx.lineTo(this.x-(t*6), this.y-(t*3));
			ctx.closePath();
			ctx.fill();
			ctx.stroke();

			ctx.fillStyle="Green";
			ctx.beginPath();
			ctx.moveTo(this.x, this.y-(t*3));
			ctx.lineTo(this.x+(t*6), this.y-(t*5));
			ctx.lineTo(this.x+(t*6), this.y-(t*3));
			ctx.lineTo(this.x, this.y-(t*3));
			ctx.closePath();
			ctx.fill();
			ctx.stroke();
      
			ctx.beginPath();
			ctx.moveTo(this.x, this.y+(t*3));
			ctx.lineTo(this.x+(t*6), this.y+(t*5));
			ctx.lineTo(this.x+(t*6), this.y+(t*3));
			ctx.lineTo(this.x, this.y+(t*3));
			ctx.closePath();
			ctx.fill();
			ctx.stroke();
      
			ctx.fillStyle="Red";
			ctx.beginPath();
			ctx.lineTo(this.x+(t*6), this.y+(t*3));
			ctx.lineTo(this.x+(t*8), this.y+(t*3));
			ctx.lineTo(this.x+(t*7), this.y+(t*2));
			ctx.lineTo(this.x+(t*8), this.y+t);
			ctx.lineTo(this.x+(t*7), this.y);
			ctx.lineTo(this.x+(t*8), this.y-t);
			ctx.lineTo(this.x+(t*7), this.y-(t*2));
			ctx.lineTo(this.x+(t*8), this.y-(t*3));
			ctx.lineTo(this.x+(t*6), this.y-(t*3));
			ctx.lineTo(this.x+(t*6), this.y+(t*3));
			ctx.closePath();
			ctx.fill();
			ctx.stroke();
      
			ctx.fillStyle="Yellow";
			circle(this.x, this.y, Math.abs(t*2.4));
			ctx.fill();
			ctx.stroke();
      
			ctx.fillStyle="White";
			circle(this.x, this.y, Math.abs(t*1.4));
			ctx.fill();
			ctx.stroke();
		}
	}
	
	
	
	Enemy.prototype.drawUFO = function(){
		ctx.fillStyle="rgba(255, 255, 255, 0.5)";
		ctx.beginPath();
		ctx.arc(this.x, this.y, ENEMY_SIZE, 0, 1.0*Math.PI, false);
		ctx.strokeStyle="rgba(255, 255, 255, 0.5)";
		ctx.stroke();
		ctx.fill();
		ctx.closePath();

		ctx.fillStyle="#99cc33";
		ctx.beginPath();
		ctx.moveTo(this.x, this.y - ENEMY_SIZE);
		ctx.lineTo(this.x - ENEMY_SIZE*1.5, this.y + ENEMY_SIZE/3);
		ctx.lineTo(this.x + ENEMY_SIZE*1.5, this.y + ENEMY_SIZE/3);
		ctx.closePath();
		ctx.fill();

		ctx.fillStyle="#339900";
		ctx.beginPath();
		ctx.rect (this.x - ENEMY_SIZE*1.5, this.y + ENEMY_SIZE/3, ENEMY_SIZE*3, ENEMY_SIZE/8);
		ctx.closePath();
		ctx.fill();
		ctx.closePath();


		ctx.fillStyle="#66ccff";
		ctx.beginPath();
		ctx.arc(this.x, this.y + (5/2), ENEMY_SIZE, 0, 1.0*Math.PI, true);
		ctx.strokeStyle="#66ccff";
		ctx.stroke();
		ctx.fill();
		ctx.closePath();

		ctx.fillStyle="#ffcc66";
		ctx.beginPath();
		ctx.arc(this.x, this.y - ENEMY_SIZE/3, ENEMY_SIZE/3, 0, 2.5*Math.PI, false);
		ctx.fill();
		ctx.closePath();

		ctx.fillStyle="white";
		ctx.beginPath();
		ctx.arc(this.x, this.y - ENEMY_SIZE/3, ENEMY_SIZE/4, 0, 2.5*Math.PI, false);
		ctx.fill();
		ctx.closePath();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#boom----------------------------------------------------------------------------------------------------------------------
	let Boom = function(x, y, type){
		this.x = x;
		this.y = y;
		this.count = 0;
		this.type = type;
		
	}
	
	Boom.prototype.draw = function(){
		if(this.type === "enemy"){
			ctx.strokeStyle = "crimson";
			ctx.lineWidth = 3;
			circle(this.x, this.y, this.count);
			ctx.stroke();
			if(this.count > 10){
				circle(this.x, this.y, this.count-10);
				ctx.stroke();
			}
		}else if(this.type === "portion"){
			ctx.font = "bold 30px Courier";
			ctx.textAlign = "center";
			ctx.textBaseline = "middle";
			ctx.fillStyle = "Hotpink";	
			ctx.fillText("HP+", this.x, this.y);
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//#portion-------------------------------------------------------------------------------------------------------------------
	let Portion = function(x, y, xSpeed, ySpeed){
		this.x = x;
		this.y = y;
		this.xSpeed = xSpeed;
		this.ySpeed = ySpeed;
		this.r = PORTION_SIZE;
	}
	
	Portion.prototype.draw = function(){
		let r = this.r / 20;
		ctx.fillStyle = "hotpink";
		ctx.beginPath();
		ctx.moveTo(this.x, this.y-25*r);
		ctx.arc(this.x+30*r, this.y-25*r, 30*r, 0, Math.PI, true);
		ctx.arc(this.x-30*r, this.y-25*r, 30*r, 0, Math.PI, true);
		ctx.lineTo(this.x-50*r, this.y+5*r);
		ctx.lineTo(this.x-40*r, this.y+15*r);
		ctx.lineTo(this.x, this.y+55*r);
		ctx.lineTo(this.x+40*r, this.y+15*r);
		ctx.lineTo(this.x+50*r, this.y+5*r);
		ctx.lineTo(this.x+60*r, this.y-25*r);
		ctx.closePath();
		ctx.fill();
	}
	
	Portion.prototype.move = function(){
		this.x += this.xSpeed;
		this.y += this.ySpeed;
		
		if(this.x <= 10+this.r || this.x >= width-10-this.r){
			this.xSpeed = -this.xSpeed;
		}
		if(this.y <= 10+this.r || this.y >= height-10-this.r){
			this.ySpeed = -this.ySpeed;
		}
	}
	
	
	
	
	//#dot-----------------------------------------------------------------------------------------------------------------------
	let Dot = function(x, y, r, color){
		this.x = x;
		this.y = y;
		this.r = r;
		this.color = color+color+color;
	}
	
	Dot.prototype.draw = function(){
		ctx.fillStyle = "#" + this.color;
		ctx.fillRect(this.x, this.y, this.r, this.r);
	}




	//#storeRect-----------------------------------------------------------------------------------------------------------------
	let StoreRect = function(x, y, w, h){
		this.x = x;
		this.y = y;
		this.w = w;
		this.h = h;
		this.l = 1;
	}
	
	StoreRect.prototype.draw = function() {
		ctx.lineWidth = this.l;
		ctx.strokeStyle = "white";
		ctx.strokeRect(this.x, this.y, this.w, this.h);
	}
	
	
	
	//set variables--------------------------------------------------------------------------------------------------------------
	
	let userName;
	let score = 0;
	let highScore = localStorage.getItem('HighScore')? parseInt(localStorage.getItem('HighScore')) : 0;
	let point = localStorage.getItem('Credit')? parseInt(localStorage.getItem('Credit')) : 0;
	let start = false;
	let store = false;
	let rank = false;
	let quadtree = false;
	let boundary = new Rectangle(0, 0, width, height);
	let ship = new Ship(width/2, height/2);
	let arrow = new Arrow();
	let shipShoots = [];
	let enemies = [];
	let booms = [];
	let portions = [];
	let dots = [];
	let gachonImg = new Image();
	let arrowValue = 15;
	gachonImg.src = IMG_SRC;
	for(let i=0 ; i<250 ; i++){
		dots.push(new Dot(random(width), random(height), random(4), random(14).toString(16)));
	}
	let storeShips = [];
	
	let storeRects = [];
	for(let i=0 ; i<4 ; i++){
		storeRects.push(new StoreRect((i+1)*300-100, 50, 200, 250));
		storeRects.push(new StoreRect((i+1)*300-100, 325, 200, 250));
	}
	for(let i=0 ; i<8 ; i++){
		storeShips.push(new Ship(storeRects[i].x + storeRects[i].w/2, storeRects[i].y + storeRects[i].h*0.8));
		if(i%2 == 0){
			storeShips[i].shootStatus = i/2 + 1;
		}else{
			storeShips[i].skillStatus = (i-1)/2 +1;
		}
		storeShips[i].sin = 1;
	}
	let storeSelect = 0;
	let storeCount = 0;
	let cost = [0, 0, 0, 0, 0, 0, 0, 0];
	notEnoughPoint = false;
	enoughPoint = false;
	finalBuyCheck = true;
	let shipShootStatus = 0;
	let shipSkillStatus = 0;
	
	
	//event handler--------------------------------------------------------------------------------------------------------------
	
	canvas.addEventListener("click", function (event){
		if(start){
			ship.shoot();
		}		
	});
	
	BODY.addEventListener("mousemove", function(event){
		let bit = Math.sqrt((event.pageX-ship.x)*(event.pageX-ship.x) + (event.pageY-ship.y)*(event.pageY-ship.y));
		ship.sin = -(event.pageY-ship.y) / bit;
		ship.cos = (event.pageX-ship.x) / bit;
	});
	
	BODY.addEventListener("keydown", function (event){
		let direction = keyActions[event.keyCode];
		if(start){
			if(direction === "esc"){
				clearStatus();
			}else{
				ship.setDirection(direction);
			}
			
		}else{
			if(store){
				if(notEnoughPoint === false && enoughPoint === false){
					storeMove(direction);
				}else if(notEnoughPoint && direction === "enter"){
					notEnoughPoint = false;
				}else if(enoughPoint){
					buyEvent(direction);
				}
			}else if(rank){
				rankMove(direction);
			}else{
				readyMove(direction);
			}
		}
		
	});
	
	let keyActions = {
		13: "enter",
		32: "space",
		
		65: "left",
		87: "up",
		68: "right",
		83: "down",
		
		192: "quadtree",
		
		38: "arrowUp",
		40: "arrowDown",
		37: "arrowLeft",
		39: "arrowRight",
		
		27: "esc",
		8: "backspace"
	};
	
	function storeMove(direction){
		if(direction === "arrowDown" && storeSelect%2 == 0){
			storeRects[storeSelect].l = 1;
			storeSelect += 1;
		}
		if(direction === "arrowUp" && storeSelect%2 == 1){
			storeRects[storeSelect].l = 1;
			storeSelect -= 1;
		}
		if(direction === "arrowLeft" && storeSelect > 1){
			storeRects[storeSelect].l = 1;
			storeSelect -= 2;
		}
		if(direction === "arrowRight" && storeSelect < 6){
			storeRects[storeSelect].l = 1;
			storeSelect += 2;
		}
		if(direction === "esc" || direction === "backspace"){
			store = false;
		}
		if(direction === "enter"){
			buyItem(storeSelect);
		}
	}
	function readyMove(direction){
		if(direction === "arrowDown" && (arrow.y === START_Y || arrow.y === STORE_Y)){
			arrow.y += 75;
		}
		if(direction === "arrowUp" && (arrow.y === STORE_Y || arrow.y === RANK_Y)){
			arrow.y -= 75;
		}
		if(direction === "enter"){
			if(arrow.y === START_Y){
				enemies = [];
				shipShoots = [];
				start = true;
			}else if(arrow.y === STORE_Y){
				store = true;
			}else if(arrow.y === RANK_Y){
				rank = true;
			}
		}
	}
	
	function buyEvent(direction){
		if(direction === "arrowRight" && finalBuyCheck === true){
			finalBuyCheck = false;
		} else if (direction === "arrowLeft" && finalBuyCheck === false){
			finalBuyCheck = true;
		} else if (direction === "enter" && finalBuyCheck === false){
			enoughPoint = false;
			finalBuyCheck = true;
		} else if (direction === "enter" && finalBuyCheck === true){
			ActualBuyItem(storeSelect);
		}
	}
	
	function clearStatus(){
		shipShoots = [];
		enemies = [];
		booms = [];
		portions = [];
		ship = new Ship(width/2, height/2);
		start = false;
		quadtree = false;
		score = 0;
	}
	
	function rankMove(direction){
		if(direction === "esc"){
			rank = false;
		}
	}
	
	//main game functions--------------------------------------------------------------------------------------------------------
	
	let intervalId = setInterval(function() {
		if(start){
			playGame();
		} else{
			if(store){
				gameStore();
			}else if(rank){
				showRank();
			}else{
				readyGame();
			}
		}
	}, 30);
	
	
	
	function playGame(){
		statusSetting();
		if(random(100) > 95){
			randomEnemy();
		}
		ctx.fillStyle = "Black";
		ctx.fillRect(0, 0, width, height);
		dots.forEach((dot) => {dot.draw();});
		activate();
		drawBorder();
		drawScore();
	}
	function statusSetting(){
		if(ship.shootStatus !== shipShootStatus){
			ship.shootStatus = shipShootStatus;
		}
		if(ship.skillStatus !== shipSkillStatus){
			ship.skillStatus = shipSkillStatus;
		}
	}
	
	function quadtreeActivate(){
		if(quadtree){
			let qtree = new QuadTree(boundary, 1);
			qtree.refresh();
			qtree.show();
		}
	}
	
	function shootActivate(){
		for(let i=0 ; i < shipShoots.length; i++){
			shipShoots[i].move();
			shipShoots[i].draw();
			if(!shipShoots[i].use){
				if(!shipShoots[i].my){
					score++;
					ship.mp += 0.1;
					if(ship.mp >= 100){
						ship.mp = 100;
					}
				}
				shipShoots.splice(i, 1);
				i--;
			}
		}	
	}
	
	function shipActivate(ship){
		ship.move();
		ship.draw();
		ship.checkCollision();
	}
	
	function enemyActivate(){
		for(let i=0 ; i < enemies.length ; i++){
			enemies[i].move();
			enemies[i].draw();
			if(!enemies[i].use || enemies[i].checkCollision(i)){
				enemies.splice(i, 1);
				i--;
			}
		}
	}
	
	function boomActivate(){
		booms.forEach((boom)=> {
			boom.draw();
			boom.count+=5;
			if(boom.count >= 30){
				booms.splice(booms.indexOf(boom), 1);
			}
		});
	}
	
	function portionActivate(){
		portions.forEach((portion) => {
			portion.draw();
			portion.move();
		});
	} 
	
	function activate(){
		quadtreeActivate();
		shootActivate();
		shipActivate(ship);
		enemyActivate();
		boomActivate();
		portionActivate();
	}
	
	
	function gameStore(){
		storeRects[storeSelect].l = 5; //highlight select
		ctx.fillStyle="Black";
		ctx.fillRect(0, 0, width, height);
		dots.forEach((dot) => {dot.draw();});
		fontText(40, "ESC: HOME", 100, 650);
		fontText(40, "ENTER: BUY", 300, 650);
		updateCredit();
		fontText(40, `CURRENT CREDIT: ${point}`, 1300, 650);
		drawBorder();
		storeRects.forEach(rect =>{
			rect.draw();
		});
		storeNameText();
		storeShips.forEach(storeShip => {storeShip.draw();});
		storeCount++;
		if(storeCount > 20){
			if(storeSelect%2 == 0){
				storeShips[storeSelect].shoot();
			}else{
				storeShips[storeSelect].skill();
			}
			
			storeCount = 0;
		}
		for(let i=0 ; i < shipShoots.length; i++){
			shipShoots[i].move();
			if(shipShoots[i].y > storeRects[storeSelect].y
			&& shipShoots[i].y < storeRects[storeSelect].y + storeRects[storeSelect].h
			&& shipShoots[i].x > storeRects[storeSelect].x
			&& shipShoots[i].x < storeRects[storeSelect].x + storeRects[storeSelect].w){
				shipShoots[i].draw();
			}else {
				shipShoots[i].use = false;
			}
			if(!shipShoots[i].use){
				shipShoots.splice(i, 1);
				i--;
			}
		}
		if(enoughPoint){
			itemBuyWindow();
		}
		if(notEnoughPoint){
			notEnoughPointWindow();
		}
	}
	
	function storeNameText(){
		ctx.textAlign =  "center";
		fontText(30, "DOUBLE SHOT", storeRects[0].x + storeRects[0].w/2, storeRects[0].y + 20);
		fontText(30, `${cost[0]}`, storeRects[0].x + storeRects[0].w/2, storeRects[0].y + 50);
		fontText(30, "CIRCLE SKILL", storeRects[1].x + storeRects[1].w/2, storeRects[1].y + 20);
		fontText(30, `${cost[1]}`, storeRects[1].x + storeRects[1].w/2, storeRects[1].y + 50);
		fontText(30, "TRIPLE SHOT", storeRects[2].x + storeRects[2].w/2, storeRects[2].y + 20);
		fontText(30, `${cost[2]}`, storeRects[2].x + storeRects[2].w/2, storeRects[2].y + 50);
		fontText(30, "SKILL 2", storeRects[3].x + storeRects[3].w/2, storeRects[3].y + 20);
		fontText(30, `${cost[3]}`, storeRects[3].x + storeRects[3].w/2, storeRects[3].y + 50);
		fontText(30, "WAVE SHOT", storeRects[4].x + storeRects[4].w/2, storeRects[4].y + 20);
		fontText(30, `${cost[4]}`, storeRects[4].x + storeRects[4].w/2, storeRects[4].y + 50);
		fontText(30, "SKILL 3", storeRects[5].x + storeRects[5].w/2, storeRects[5].y + 20);
		fontText(30, `${cost[5]}`, storeRects[5].x + storeRects[5].w/2, storeRects[5].y + 50);
		fontText(30, "SPREAD SHOT", storeRects[6].x + storeRects[6].w/2, storeRects[6].y + 20);
		fontText(30, `${cost[6]}`, storeRects[6].x + storeRects[6].w/2, storeRects[6].y + 50);
		fontText(30, "SKILL 4", storeRects[7].x + storeRects[7].w/2, storeRects[7].y + 20);
		fontText(30, `${cost[7]}`, storeRects[7].x + storeRects[7].w/2, storeRects[7].y + 50);
	}
	
	function buyItem(storeSelect){
		if(cost[storeSelect] > point){
			notEnoughPoint = true;
		} else{
			enoughPoint = true;
		}
	}
	
	function notEnoughPointWindow(){
		ctx.fillStyle = "black";
		ctx.strokeStyle = "white";
		ctx.fillRect(width/2-300, height/2-150, 600, 300);
		ctx.strokeRect(width/2-300, height/2-150, 600, 300);
		fontText(40, "NOT ENOUGH CREDIT", width/2, height/2-50);
		ctx.strokeRect(width/2-100, height/2+50, 200, 50);
		fontText(40, "OK", width/2, height/2+75);
	}
	
	function itemBuyWindow(){
		ctx.fillStyle = "black";
		ctx.strokeStyle = "white";
		ctx.fillRect(width/2-300, height/2-150, 600, 300);
		ctx.strokeRect(width/2-300, height/2-150, 600, 300);
		fontText(40, "DO YOU REALLY WANT TO BUY?", width/2, height/2-50);
		if(finalBuyCheck){
			ctx.lineWidth = 5;
			ctx.strokeRect(width/2-175, height/2+50, 150, 50);
			ctx.lineWidth = 1;
			ctx.strokeRect(width/2+25, height/2+50, 150, 50);
		}else{
			ctx.lineWidth = 1;
			ctx.strokeRect(width/2-175, height/2+50, 150, 50);
			ctx.lineWidth = 5;
			ctx.strokeRect(width/2+25, height/2+50, 150, 50);
		}
		
		fontText(40, "OK", width/2-100, height/2+75);
		fontText(40, "NO", width/2+100, height/2+75);
	}
	
	function showRank(){
		ctx.fillStyle="Black";
		ctx.fillRect(0, 0, width, height);
		dots.forEach((dot) => {dot.draw();});
		drawBorder();
		fontText(40, "ESC: HOME", 100, 650);
		fontText(20, "LEFT: PREVIOUS DAY", 500, 650);
		fontText(20, "UP or DOWN: TOTAL/DAY SCORE", 900, 650);
		fontText(20, "RIGHT: NEXT DAY", 1300, 650);
	}
	
	function updateCredit(){
		let currentPoint = parseInt(localStorage.getItem("Credit"));
		if(currentPoint !== NaN){
			point = currentPoint;
		}
	}
	
	function updateHighScore(){
		let currentHighScore = parseInt(localStorage.getItem("HighScore"));
		if(currentHighScore !== NaN){
			highScore = currentHighScore	;
		}
	}
	
	function ActualBuyItem(storeSelect){
		updateCredit();
		let currentCredit = parseInt(localStorage.getItem("Credit"));
		currentCredit -= cost[storeSelect];
		localStorage.setItem("Credit", currentCredit);
		
		if(storeSelect%2 == 0){
			shipShootStatus = storeSelect/2 + 1;
			localStorage.setItem('Shot', shipShootStatus);
		}else{
			shipSkillStatus = (storeSelect-1)/2 +1;
			localStorage.setItem('Skill', shipSkillStatus);
		}
		enoughPoint = false;
	}
	
	function uploadRank(name){
		var form = document.createElement('form');
		var objs;
		objs = document.createElement('input');
		objs.setAttribute('type', 'hidden');
		objs.setAttribute('name', 'score');
		objs.setAttribute('value', score);
		form.appendChild(objs);
		objs2 = document.createElement('input');
		objs2.setAttribute('type', 'hidden');
		objs2.setAttribute('name', 'name');
		objs2.setAttribute('value', name);
		form.appendChild(objs2);

		form.setAttribute('method', 'post');
		form.setAttribute('action', "ranking_proc.php");
		document.body.appendChild(form);
		form.submit();
		
	}
	
	
	
</script>
</body>
</html>

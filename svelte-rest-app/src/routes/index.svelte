<script>
// STEP01 onMountを呼び出します
import { beforeUpdate, onMount } from 'svelte';

	let name = 'name';
	let lv = 10;
	let max_hp = 1000;
	let hp = 80;
	let max_mp = 10;
	let mp = 8;
	let agi = 5;
	let tec = 2;
	let str = 9;
	let luc = 1;
	let text = `I'm stronger.`;

	beforeUpdate(()=>{
		max_hp = lv * str;
		max_mp = Math.round((lv * agi) / 2);
	});

	// STEP01 fetchでパラメータを取得する機能
	const getParams = async () => {
		const serverURL = 'http://localhost:8080/params';
		const response = await fetch(
			serverURL,
			{
				method: 'GET',
				mode: 'cors',
				credentials: 'omit'
			}
		);
		return await response.json();
	}

	// STEP01 アプリマウント時に、取得したパラメータを変数に適用する処理
	onMount(async ()=>{
		let params = await getParams();

		name = params.name;
		lv   = params.lv;
		hp   = params.hp;
		mp   = params.mp;
		agi  = params.agi;
		tec  = params.tec;
		str  = params.str;
		luc  = params.luc;
		text = params.text;
	});

	// STEP02 送信結果を受け取る変数
	let message = false;

	// STEP02 fetchでパラメータを送信する機能
	const saveParams = async () => {
		const serverURL = 'http://localhost:8080/saveparams';
		const response = await fetch(
			serverURL,
			{
				method: 'POST',
				mode: 'cors',
				credentials: 'omit',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					name: name,
					lv: lv,
					hp: hp,
					mp: mp,
					agi: agi,
					tec: tec,
					str: str,
					luc: luc,
					text: text
				})
			}
		);
		message = await response.json();
	}
</script>

<div class="status-view">
	<div class="status-image">
		{name}<br>
		<img src="https://uploads.codesandbox.io/uploads/user/5f10fa6b-67dd-441c-bf2d-03b8f3dc3846/qsNQ-alienYellow.png" alt="character" />
	</div>
	<div class="status-data">
		Lv: {lv}<br>
		HP: {hp}/ {max_hp}<br>
		MP: {mp}/ {max_mp}<br>
		<br>
		AGI: {agi}  STR: {str}<br>
		TEC: {tec}  LUC: {luc}
	</div>
	<div class="status-text">
		{text}
	</div>
</div>

<div class="status-config">
	config<br>
	name : <input type="text" maxlength="16" bind:value="{name}" />
	<br>
	Lv : <input type="number" min="1" max="99" bind:value="{lv}" />
	<br>
	hp : <input type="number" min="0" max="{max_hp}" bind:value={hp} />
	mp : <input type="number" min="0" max="{max_mp}" bind:value={mp} />
	<br>
	AGI : <input type="number" min="1" max="99" bind:value={agi} />
	STR : <input type="number" min="1" max="99" bind:value={str} />
	<br>
	TEC : <input type="number" min="1" max="99" bind:value={tec} />
	LUC : <input type="number" min="1" max="99" bind:value={luc} />
	<br>
	text : <br>
	<textarea bind:value="{text}"></textarea>
</div>

<style>
.status-view {
	width: 320px;
	height: 240px;
	margin: 2em auto;
	padding: 1em;
	background-color: blue;
	color: white;
	display: flex;
	flex-wrap: wrap;
	text-shadow: 1px 1px black;
	font-family: monospace;
	font-size: 16px;
}
.status-image {
	width: 128px;
}
.status-data {
	flex-grow: 1;
	padding-left: 1em;
}
.status-text {
	width: 100%;
	padding: 0.5em;
	border: 2px ridge white;
	overflow: hidden;
}
img {
	display: block;
	margin: auto;
}
.status-config {
	width: 320px;
	margin: 1em auto 2em;
	padding: 1em;
	border: 1px solid black;
	line-height: 2em;
}
.status-config input[type=number] {
	max-width: 6em;
}
</style>

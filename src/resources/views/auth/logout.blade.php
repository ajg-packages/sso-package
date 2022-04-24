<style>
	body{
  background: #2a2a3c;
}
.loading{
  position: absolute;
  top: 50%;
  left: 50%;
  height: 40px;
  width: 40px;
  transform: translate(-50%, -50%) rotate(45deg) translate3d(0,0,0);
  animation: animate 1.2s ease-in-out infinite;
}
@keyframes animate {
  0%, 10%, 100%{
    height: 40px;
    width: 40px;
  }
  65%{
    height: 70px;
    width: 70px;
  }
}
span{
  position: absolute;
  display: block;
  width: 20px;
  height: 20px;
  animation: rotate 1.2s linear both infinite;
}
@keyframes rotate {
  0%, 30%{
    transform: rotate(0);
  }
  65%{
    transform: rotate(-40deg);
  }
}
span:nth-child(1){
  top: 0;
  left: 0;
  background: #1ac9ff;
}
span:nth-child(2){
  top: 0;
  right: 0;
  background: #fcb603;
}
span:nth-child(3){
  bottom: 0;
  left: 0;
  background: #af49b6;
}
span:nth-child(4){
  bottom: 0;
  right: 0;
  background: #e03870;
}

</style>
<div class="loading">
  <span></span>
  <span></span>
  <span></span>
  <span></span>
</div>
<img src="{{env('AUTHORIZATION_SERVER_URL', 'https://auth.cyrexag.com')}}/logout" style="visibility: hidden;">

<script type="text/javascript">
	setTimeout(function(){
    window.location = "/";
  }, 1500);
    
</script>
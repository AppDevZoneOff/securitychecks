/////helper function in laravel (app/helper.php)

function addBlock(){
         $time = 50;
         $countCondition = 5;
         $status="unblock";
  
        if (Cookie::get('first_time')) {
        $dataFetchValue = (array) json_decode(Cookie::get('first_time'));
       
        if ($dataFetchValue['count'] == $countCondition) {
                 
            $last = strtotime(date('Y-m-d h:i:s'));
            $curr = strtotime($dataFetchValue['first_time']);
            $sec = abs($last - $curr);
            if ($sec <= 30) {
                
          return 'block' ;

            } else {
              
                 $cookie = Cookie::queue('first_time', null, -2628000, null, null);
              
               return  $status ;
            }
           }

           $data = json_encode(['first_time' => $dataFetchValue['first_time'], 'count' => $dataFetchValue['count'] + 1, 'ip' => $dataFetchValue['ip'], 'block' => Str::random(2)]);

           $cookie = Cookie::queue('first_time', $data, $time);
           return $status ;
           }
           $data = json_encode(['first_time' => date('Y-m-d h:i:s'), 'count' => 1, 'ip' => request()->ip(), 'block' => Str::random(10)]);

          $cookie = Cookie::queue('first_time', $data, $time);
         return  $status ; 
            
}
//////////////////////////////end helper//////////////////////////////
/////////////////////////////////////session add block ///////////////
  if (Session('ip')) {
        if (Session('ip') == $_SERVER['REMOTE_ADDR'] || Session('mac') == exec('getmac')) {
            Session::put('LAST_CALL', date('Y-m-d h:i:s'));
            if (Session('count') == 5) {
                if (Session('LAST_CALL')) {
                    $last = strtotime(Session('LAST_CALL'));
                    $curr = strtotime(Session('first_call'));
                    $sec = abs($last - $curr);
                    if ($sec <= 60) {
                    } else {
                        Session::forget(['ip', 'mac', 'first_call', 'LAST_CALL', 'count']);
                    }
                }
            } else {
                Session::put('count', Session('count') + 1);
            }
        }
    }

    Session::put('ip', $_SERVER['REMOTE_ADDR']);
    Session::put('mac', exec('getmac'));
    if (!Session('first_call')) {
        Session::put('count', 1);
        Session::put('first_call', date('Y-m-d h:i:s'));
    }
    return view('welcome');
/////////////////////////////////session code send for add block/////////////////////////////////////////////////////
    
 ////////////////////////////login att///////////////////////////////////
     
    $time = 36000;
       $countCondition = 3;
        
       
         $user = User::where('email',$request->email)->first(); 
         
       
        
            
             
             if($user){
         if($user->block_user==0 ){
           $credentials = $request->only('email', 'password');
          if (Auth::attempt($credentials)) {
           $user->update(['login_ip'=>$request->ip()]);
            $cookie = Cookie::forget('first_time');
            if($user->role=='admin'){
                 return redirect('admin/dashboard')->withCookie($cookie);
            }
            return redirect('/')->withCookie($cookie);
        }
         }
       
  
       
          if($user->block_user == 1  && Cookie::get('first_time')){
             $dataFetchValue =(array) json_decode(Cookie::get('first_time'));

        if($dataFetchValue['count']>=$countCondition){
             return redirect()->route('password.request')->with('message','Request is block send reset mail on your mail');
           }
        }
        
           
        
              }
      
      

    if(Cookie::get('first_time')){
        $dataFetchValue =(array) json_decode(Cookie::get('first_time'));

        if($dataFetchValue['count']>=$countCondition){

            $last = strtotime(date("Y-m-d h:i:s"));
            $curr = strtotime($dataFetchValue['first_time']);
            $sec =  abs($last - $curr);
            if ($sec <= 120000) {
                    if($user){
                         $user->update(['block_user'=>1]);
                    }
               
           return redirect()->route('password.request')->with('message','Request is block send reset mail on your mail');
            }else{



                $cookie = Cookie::forget('first_time');
                return redirect()->back()->withCookie($cookie);

            }

        }
         
        $data = json_encode(

            array('block'=>rand(99999,9999999),'first_time' => $dataFetchValue['first_time'],'count'=>$dataFetchValue['count']+1,'ip'=>$dataFetchValue['ip'] )
        );
        
             
        $cookie = cookie('first_time',$data, $time);
       return redirect()->back()->withCookie($cookie);
    }
    $data = json_encode(

        array('first_time' => date("Y-m-d h:i:s"),'count'=>1,'ip'=>$request->ip() )

    );

      $cookie = cookie('first_time',$data, $time);
      
    

          return redirect()->back()->with('message','Try again.')->cookie($cookie);
 
        
          
      
    }
 //////////////////////////////////////////////////att end/////////////////////////////////////////
 
              
 //////////////////////////////////////////////ip login one user//////////////////////////////////////
  ip_login [Request ip]
  user_block type [0,1]
              login inside update query 
              
////////////////////////////////////////////////////////                                  
                                  
                                  
                                  
                                  
                                  
        

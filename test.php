
        <?php
        


           $coachDates =json_decode('{"2025-02-24":[{"id":"1741128263259","startTime":"12:00","endTime":"14:00"},{"id":"1741128265698","startTime":"18:00","endTime":"20:00"}],"2025-02-25":[{"id":"1741128269288","startTime":"14:00","endTime":"18:00"}],"2025-02-26":[{"id":"1741128272549","startTime":"13:00","endTime":"15:00"},{"id":"1741128276166","startTime":"17:00","endTime":"19:00"}]}');
         //   $date = array_keys($coachDates);
         // foreach ($coachDates as $key => $value) {
         //    print
         // }

   
        

         //   $exist = '';
         //   if(!$exist){
         //    print('empty ');
         //   }
           foreach ($coachDates as $d=>$v) {
            // print($d);
            $coachDay = date('l', strtotime($d));
            $currentDay = date('l', strtotime('2025-03-03'));
            if ($coachDay === $currentDay) {
                 $exist = $coachDay;
               //   print($coachDay);
                 break;
             }
             
         }

         if ($exist) {
            print($exist);
        }
      //   print('hello');
        ?>
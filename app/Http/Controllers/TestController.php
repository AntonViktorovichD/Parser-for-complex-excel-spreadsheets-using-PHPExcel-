<?php
//
//namespace App\Http\Controllers;
//
//use App\Http\Requests;
//use Illuminate\Support\Facades\DB;
//
//class TestController extends Controller {
//   public function test() {
//      $orgs = DB::table('org_helper')->where('type', NULL)->get();
//      $dists = DB::table('dist')->where('id', '>', 59)->get();
//      $curs = [];
////      echo '<pre>';
////      var_dump($orgs);
////      echo '</pre>';
//
//      foreach ($dists as $key => $dist) {
//         $curs[$key][$dist->type] = preg_replace('#\.+#', ' ', preg_replace('#НО #', '', preg_replace('#ГКУ #', '', preg_replace('#ГБУ #', '', preg_replace('#["\']+#', '', $dist->other_title)))));
//      }
//
//      foreach ($orgs as $org) {
//         foreach ($curs as $cur) {
//            foreach ($cur as $key => $item) {
//               if (strlen($item) > 0) {
////                  var_dump(preg_replace('#ГБУ #', '', preg_replace('#ГКУ #', '', preg_replace('#["\']+#', '', $org->title))));
////                  if ($item == preg_replace('#НО #', '', preg_replace('#ГБУ #', '', preg_replace('#ГКУ #', '', preg_replace('#["\']+#', '', $org->title))))) {
//                  if ($item == preg_replace('#["\'.]+#', ' ', $org->title)) {
//                     var_dump($key);
//                     DB::table('org_helper')->where('id', $org->id)->update(['type' => $key]);
//                  }
//               }
////
//            }
//         }
//      }
//
//
//      return view('test');
//   }
//}

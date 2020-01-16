<?php

namespace App\Http\Controllers;
use DB;
use App\Model\FilePKM;
use App\Model\NilaiPKM;
use Illuminate\Http\Request;

class PostReviewerController extends Controller
{
    /////JANGAN DIHAPUS /////
    //ini konstraktor buat middleware sebagai Admin
    public function __construct()
    {
        $this->middleware('reviewer');
        //$this->middleware(['reviewer', 'sso']);
    }
    ////////////////////////////////////////////////

    public function InputNilai(Request $request)
    {
        DB::beginTransaction();
            try {
                $idpkm = $request->get('id');
                $pkm = FilePKM::whereid($idpkm)->first();

                if($pkm->id_skim_pkm == 1 || $pkm->id_skim_pkm == 2){
                    //PSH dan PE
                    $a = NilaiPKM::whereid_file_pkm($idpkm)->first();
                    $a->proposal1 = $request->get('v1');
                    $a->proposal2 = $request->get('v2');
                    $a->proposal3 = $request->get('v3');
                    $a->proposal4 = $request->get('v4');
                    $a->proposal5 = $request->get('v5');
                    $a->proposal6 = $request->get('v6');
                    $a->proposal7 = $request->get('v7');
                    $a->proposal8 = $request->get('v8');
                    $a->proposal9 = $request->get('v9');
                    $a->note_proposal = $request->get('notenilai');
                    $a->save();

                    return redirect()->back()->with('nilai', 'Success');
                } elseif($pkm->id_skim_pkm == 3){
                    //T
                    $a = NilaiPKM::whereid_file_pkm($idpkm)->first();
                    $a->proposal1 = $request->get('v1');
                    $a->proposal2 = $request->get('v2');
                    $a->proposal3 = $request->get('v3');
                    $a->proposal4 = $request->get('v4');
                    $a->proposal5 = $request->get('v5');
                    $a->proposal6 = $request->get('v6');
                    $a->proposal7 = $request->get('v7');
                    $a->note_proposal = $request->get('notenilai');
                    $a->save();
                } elseif($pkm->id_skim_pkm == 4){
                    //K
                    $a = NilaiPKM::whereid_file_pkm($idpkm)->first();
                    $a->proposal1 = $request->get('v1');
                    $a->proposal2 = $request->get('v2');
                    $a->proposal3 = $request->get('v3');
                    $a->proposal4 = $request->get('v4');
                    $a->proposal5 = $request->get('v5');
                    $a->proposal6 = $request->get('v6');
                    $a->proposal7 = $request->get('v7');
                    $a->note_proposal = $request->get('notenilai');
                    $a->save();
                } elseif($pkm->id_skim_pkm == 5){
                    //KC
                    $a = NilaiPKM::whereid_file_pkm($idpkm)->first();
                    $a->proposal1 = $request->get('v1');
                    $a->proposal2 = $request->get('v2');
                    $a->proposal3 = $request->get('v3');
                    $a->proposal4 = $request->get('v4');
                    $a->proposal5 = $request->get('v5');
                    $a->proposal6 = $request->get('v6');
                    $a->proposal7 = $request->get('v7');
                    $a->note_proposal = $request->get('notenilai');
                    $a->save();
                } elseif($pkm->id_skim_pkm == 6){
                    //M
                    $a = NilaiPKM::whereid_file_pkm($idpkm)->first();
                    $a->proposal1 = $request->get('v1');
                    $a->proposal2 = $request->get('v2');
                    $a->proposal3 = $request->get('v3');
                    $a->proposal4 = $request->get('v4');
                    $a->proposal5 = $request->get('v5');
                    $a->proposal6 = $request->get('v6');
                    $a->proposal7 = $request->get('v7');
                    $a->note_proposal = $request->get('notenilai');
                    $a->save();
                } elseif($pkm->id_skim_pkm == 7){
                    //GT
                    $a = NilaiPKM::whereid_file_pkm($idpkm)->first();
                    $a->proposal1 = $request->get('v1');
                    $a->proposal2 = $request->get('v2');
                    $a->proposal3 = $request->get('v3');
                    $a->proposal4 = $request->get('v4');
                    $a->note_proposal = $request->get('notenilai');
                    $a->save();
                } elseif($pkm->id_skim_pkm == 8){
                    //AI
                    $a = NilaiPKM::whereid_file_pkm($idpkm)->first();
                    $a->proposal1 = $request->get('v1');
                    $a->proposal2 = $request->get('v2');
                    $a->proposal3 = $request->get('v3');
                    $a->proposal4 = $request->get('v4');
                    $a->proposal5 = $request->get('v5');
                    $a->proposal6 = $request->get('v6');
                    $a->proposal7 = $request->get('v7');
                    $a->proposal8 = $request->get('v8');
                    $a->note_proposal = $request->get('notenilai');
                    $a->save();
                } elseif($pkm->id_skim_pkm == 9){
                    //GFK
                    $a = NilaiPKM::whereid_file_pkm($idpkm)->first();
                    $a->proposal1 = $request->get('v1');
                    $a->proposal2 = $request->get('v2');
                    $a->proposal3 = $request->get('v3');
                    $a->proposal4 = $request->get('v4');
                    $a->note_proposal = $request->get('notenilai');
                    $a->save();
                }
                DB::commit();
                return redirect()->back()->with('nilai', 'Success');
            } catch (\Exception $e) {
                DB::rollback();
            }
        }
}

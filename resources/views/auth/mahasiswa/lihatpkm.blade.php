@extends('layout.master')

@section('title')
List PKM Mahasiswa
@endsection


@section('header')
<link rel="stylesheet" type="text/css" href="{{url('assets/css/validationEngine.jquery.css')}}">
@endsection

@section('content')
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="icofont icofont-speed-meter bg-c-blue"></i>
                <div class="d-inline">
                    <h4>Detail Proposal PKM</h4>
                    <span><strong>{{$pkm->skim_lengkap}}</strong></span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{url('/')}}"> <i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">List PKM</a> </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->

@if ($jumlahanggota < 2) <div class="alert alert-danger">
    @if($pkm->status_edit == 1 && $pkm->aktif == 1)
    Belum ada Anggota
    @else
    Sistem Upload sudah ditutup dan Belum ada Anggota
    @endif
    </div>
    @elseif($jumlahanggota > 1 && $pkm->file_proposal == "")
    <div class="alert alert-danger">
        @if($pkm->status_edit == 1 && $pkm->aktif == 1)
        File Belum Diupload
        @else
        Sistem Upload sudah ditutup dan File Belum Diupload
        @endif
    </div>
    @endif
    <!-- Page-body start -->
    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="f-right btn-group">
                            @if ($pkm->proposal1 != "" || $pkm->proposal1 != NULL)
                            	<button data-toggle="modal" data-target="#modalNilai" class="btn btn-mini btn-info">Penilaian Proposal</button>
                            @endif
                            @if ($pkm->perekap_wawancara != "" || $pkm->pekap_wawancara != NULL)
                            	<button class="btn btn-mini btn-info">Nilai primary</button>
                            @endif
                            @if ($saya->acc_anggota == "Y" && $pkm->status_edit == 1 && $pkm->aktif == 1)
                            	<button class="editPKM btn btn-mini btn-warning">Edit PKM</button>
                            	@if(Auth::user()->identitas_mahasiswa->id == $ketuapkm->id_mahasiswa && $pkm->status_hapus == 1)
                            		<button class="hapusPKM btn btn-mini btn-danger">Hapus PKM</button>
                            	@endif
                            @endif
                        </div>
                        <h5>
                            @if ($pkm->status == "1")
                            Pengajuan Proposal
                            @elseif($pkm->status == "2")
                            Lolos Proses Upload
                            @elseif($pkm->status == "3")
                            Lolos Didanai
                            @elseif($pkm->status == "4")
                            Lolos Pimnas
                            @else
                            {{$pkm->status}}
                            @endif
                            <br><small>
                                Jenis PKM : {{$pkm->tipe}}
                                @if($pkm->status_edit == 1 && $pkm->aktif == 1)
                                <strong class="text-success"> (Aktif)</strong>
                                @else
                                <strong class="text-danger"> (Tidak Aktif)</strong>
                                @endif
                            </small>
                        </h5>
                    </div>
                    <div class="card-block">
                        <div class=" col-md-10 m-auto">
							<div class="row">
								<div class="col-md-12 mb-3">
									<h6><strong>Judul PKM : </strong></h6> 
									<h6>{{$pkm->judul}}</h6>
								</div>
								@if ($pkm->id_tipe_pkm == "2" || $pkm->id_tipe_pkm == "3" || $pkm->id_tipe_pkm == "4")
								<div class="col-md-12 mb-3">
									<h6><strong>Abstrak :</strong></h6>
									<h6>{{$pkm->abstrak}}</h6>
								</div>
								@endif	
								<div class="col-md-6 mb-3">
									<h6><strong>Keyword :</strong></h6>
									<h6>{{$pkm->keyword}}</h6>
								</div>
								<div class="col-md-6 mb-3">
									<h6><strong>Usulan PKM :</strong></h6>
									@if($pkm->self == "N")
									<label class="label label-success"> Atas Keinginan Sendiri</label>
									@else
									<label class="label label-warning"> Pengajuann Atas Kewajiban</label>
									@endif
								</div>
								<div class="col-md-6 mb-3">
									<h6><strong>Skim PKM :</strong></h6>
									<h6>{{$pkm->skim_lengkap}}</h6>
								</div>	
								<div class="col-md-6 mb-3">
									<h6><strong>Dosen Pembimbing :</strong></h6>
									<h6>{{$pkm->nama_dosen}}</h6>
								</div>	
								<div class="col-md-6 mb-3">
									@if($pkm->nidn_dosen == "" && $pkm->nidk_dosen == "")
										<h6><strong>NIP Dosen :</strong></h6>
										<h6>{{$pkm->nip_dosen}}</h6>
									@else
										@if($pkm->nidn_dosen == "")
										<h6><strong>NIDK Dosen :</strong></h6>
										<h6>{{$pkm->nidk_dosen}}</h6>
										@else
										<h6><strong>NIDN Dosen :</strong></h6>
										<h6>{{$pkm->nidn_dosen}}</h6>
										@endif
									@endif
								</div>

								@if($pkm->id_tipe_pkm == "1" || $pkm->id_tipe_pkm == "4")
								<div class="col-md mb-3">
									<h6><strong>Dana Pengajuan :</strong></h6>
									<h6>Rp. {{number_format($pkm->dana_pkm, 0, ".", ".")}},-</h6>
								</div>
								<div class="col-md-6 mb-3">
									<h6><strong>Durasi Penelitian :</strong></h6>
									<h6>{{$pkm->durasi}} Bulan</h6>
								</div>
								@endif

								@if($pkm->id_tipe_pkm == "3")
								<div class="col-md-6 mb-3">
									<h6><strong>URL Video :</strong></h6>
									<h6><a target="_blank" href="{{$pkm->linkurl}}">{{$pkm->linkurl}}</a></h6>
								</div>
								@endif

								<!-- Akun Simbelmawa -->
								@if($pkm->status != "1")
									@if($pkm->confirm_up != "")
										<div class="col-md-6 mb-3">
											<h6><strong>Akun Simbelmawa :</strong></h6>
											<h6><a href="https://simbelmawa.ristekdikti.go.id" target="_blank" class="text-danger" rel="noopener noreferrer">https://simbelmawa.ristekdikti.go.id</a></h6>
											<h6>Username : 001038{{$ketuapkm->nim}}</h6>
											<h6>Password : {{$ketuapkm->pass_simbel}}</h6>
										</div>
									@else
										<div class="col-md-6 mb-3">
											<h6><strong>Akun Simbelmawa :</strong></h6>
											<button class="btn btn-warning" data-toggle="modal" data-target="#konfirUpload">Konfirmasi PKM</button>
										</div>
									@endif
								@endif

								<div class="col-md-12 mb-3">
									<h6><strong>Files PKM:</strong></h6>
									<div class="row">
										@if($pkm->file_proposal != "" || $pkm->file_proposal != NULL)
											@if($modetemplate->konten == "1" && $cekacc == 0 && $jumlahanggota >= $pkm->minimal_skim)
												<div class="col-md-2">
													<h6><a href="{{url('mhs/downlempeng')}}/{{Crypt::encryptString($id)}}"><button class="templateBtn btn btn-mini btn-primary">Download Template</button></a></h6>
												</div>
                                    		@endif
											<div class="col-md-2">
												<h6><a href="{{url('mhs/downpro')}}/{{Crypt::encryptString($id)}}"><button class="btn btn-mini btn-success">Download Proposal</button></a></h6>
											</div>
										@endif

										@if ($cekacc == 0 && $jumlahanggota >= $pkm->minimal_skim)
                                    		@if($pkm->file_proposal != "" || $pkm->file_proposal != NULL)
												@if ($saya->acc_anggota == "Y" && $pkm->status_upload == 1 && $pkm->aktif == 1)
												<div class="col-md-2">
													<h6><button class="revisiProposal btn btn-mini btn-danger">Upload Ulang Proposal</button></h6>
												</div>
												@endif
                                    		@else
												@if ($saya->acc_anggota == "Y" && $pkm->status_upload == 1 && $pkm->aktif == 1)
													@if($modetemplate->konten == "1")
														<div class="col-md-2">
															<h6><a href="{{url('mhs/downlempeng')}}/{{Crypt::encryptString($id)}}"><button class="templateBtn btn btn-sm btn-primary">Download Template</button></a></h6>
														</div>
													@else
														<div class="col-md-2">
															<h6><button class="templateBtn btn btn-sm btn-default disabled">Download Template</button></h6>
														</div>
													@endif
												<div class="col-md-2">
													<h6><button class="uploadProposal btn btn-sm btn-warning" data-kode="{{Crypt::encryptString($id)}}">Upload Proposal</button></h6>
												</div>
												@endif
                                   			 @endif
										@else
											<div class="col-md-12">
												<h6 class="text-danger"><strong>Untuk mengunduh file template dan mengunggah file Proposal maka<br>Lengkapi Anggota terlebih dahulu dan mintalah anggota Anda untuk menerima PKM Anda.</strong></h6>
											</div>	
										@endif
									</div>
								</div>			
							</div>
                        </div>
                        <hr>
                        <div>
                            @if (Auth::user()->identitas_mahasiswa->id == $ketuapkm->id_mahasiswa && $pkm->status_edit == 1 && $pkm->aktif == 1 && $jumlahanggota < $pkm->maksimal_skim)
                                <button class="f-right btn btn-mini btn-success tambahAnggota">Tambah Anggota</button>
                                @endif
                                <h6>Jumlah Anggota yang PKM : {{$jumlahanggota}}</h6>
                        </div>
                        <br>
                        <div class="dt-responsive table-responsive">
                            <table class="table table-striped table-bordered nowrap">
                                <thead>
                                    <th width="10%">No.</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Peran</th>
                                    @if (Auth::user()->identitas_mahasiswa->id == $ketuapkm->id_mahasiswa && $pkm->status_edit == 1 && $pkm->aktif == 1)
                                    <th>Status</th>
                                    <th>Aksi</th>
                                    @elseif (Auth::user()->identitas_mahasiswa->id != $ketuapkm->id_mahasiswa && $pkm->status_edit == 1 && $pkm->aktif == 1)
                                    <th>Aksi</th>
                                    @endif
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{$ketuapkm->nama}}</td>
                                        <td>{{$ketuapkm->nama_prodi}}</td>
                                        <td>{{$ketuapkm->jabatan}}</td>
                                        @if (Auth::user()->identitas_mahasiswa->id == $ketuapkm->id_mahasiswa && $pkm->status_edit == 1 && $pkm->aktif == 1)
                                        <td><label class="label label-success">Aktif</label></td>
                                        <td><button data-toggle="tooltip" title="Ketua tidak bisa dihapus" class="btn btn-mini btn-danger disabled">X</button></td>
                                        @elseif(Auth::user()->identitas_mahasiswa->id != $ketuapkm->id_mahasiswa && $pkm->status_edit == 1 && $pkm->aktif == 1)
                                        <td><span class="text-success">Aktif</span></td>
                                        @endif
                                    </tr>
                                    @if($jumlahanggota == 2)
                                    <?php $anggotanya = "Anggota 1"; ?>
                                    @elseif($jumlahanggota == 3)
                                    <?php $anggotanya = "Anggota 2"; ?>
                                    @elseif($jumlahanggota == 4)
                                    <?php $anggotanya = "Anggota 3"; ?>
                                    @elseif($jumlahanggota == 5)
                                    <?php $anggotanya = "Anggota 4"; ?>
                                    @elseif($jumlahanggota == 6)
                                    <?php $anggotanya = "Anggota 5"; ?>
                                    @endif
                                    @if ($jumlahanggota > 1)
                                    <?php $n = 2; ?>
                                    @foreach ($anggotapkm as $t)
                                    <tr>
                                        <td>{{$n}}</td>
                                        <td>{{$t->nama}}</td>
                                        <td>{{$t->nama_prodi}}</td>
                                        <td>{{$t->jabatan}}</td>
                                        @if (Auth::user()->identitas_mahasiswa->id == $ketuapkm->id_mahasiswa && $pkm->status_edit == 1 && $pkm->aktif == 1)
                                        <td>
                                            @if($t->acc_anggota == "Y")
                                            <label class="label label-success">Aktif</label>
                                            @else
                                            <label class="label label-warning">Belum Menerima</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t->jabatan == $anggotanya)
                                            <button data-toggle="tooltip" data-namaanggota="{{$t->nama}}" data-kodedata="{{Crypt::encryptString($t->id_mahasiswa)}}" title="Hapus {{$t->nama}}" class="btn btn-mini btn-danger hapusAnggota">X</button>
                                            @else
                                            <button data-toggle="tooltip" title="Hapus {{$anggotanya}} terlebih dahulu" class="btn btn-mini btn-danger disabled">X</button>
                                            @endif
                                        </td>
                                        @elseif (Auth::user()->identitas_mahasiswa->id != $ketuapkm->id_mahasiswa && $pkm->status_edit == 1 && $pkm->aktif == 1)
                                        @if(Auth::user()->identitas_mahasiswa->id == $t->id_mahasiswa)
                                        <td>
                                            @if ($t->acc_anggota == "Y")
                                            <span class="text-success">Aktif</span>
                                            @else
                                            <button data-kode="{{Crypt::encryptString($t->id_mahasiswa)}}" class="aktifkanAnggota btn btn-info btn-mini">Terima ?</button>
                                            @endif
                                        </td>
                                        @else
                                        <td>
                                            @if($t->acc_anggota == "Y")
                                            <span class="text-success">Aktif</span>
                                            @else
                                            <span class="text-danger">Belum Menerima</span>
                                            @endif
                                        </td>
                                        @endif
                                        @endif
                                    </tr>
                                    <?php $n++; ?>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" class="text-center f-s-italic text-danger"><strong>Belum ada Anggota PKM !</strong></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Basic Button table end -->
            </div>
        </div>
    </div>
    <!-- Page-body end -->
    @endsection



    @section('end')
    
    {{--Ini End--}}

    @if (Auth::user()->identitas_mahasiswa->id == $ketuapkm->id_mahasiswa && $pkm->status_edit == 1 && $pkm->aktif == 1)

        @if($jumlahanggota < $pkm->maksimal_skim)
    	    <div class="modal fade" id="tambah-anggota" tabindex="-1" role="dialog" aria-labelledby="tambahAnggotaLabel" aria-hidden="true">
    	    	<div class="modal-dialog">
    	    		<div class="modal-content">
    	    			<div class="modal-header">
    	    				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    	    				<h4 class="modal-title" id="tambahAnggotaLabel">Tambah Anggota</h4>
    	    			</div>

    	    			<form action="{{url('mhs/tambahanggota')}}" class="form-validation" method="POST">
    	    				@csrf
    	    				<input type="hidden" name="kode_token" value="{{Crypt::encryptString($id)}}">
    	    				<input type="hidden" name="kode" id="kode">
    	    				<div class="modal-body">
    	    					<div class="form-group">
    	    						<div class="input-group">
    	    							<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
    	    							<input type="text" name="nim" id="nim" class="validate[required,custom[integer]] form-control" placeholder="NIM Anggota">
    	    							<span class="input-group-addon cursor-hand" id="search">Cari <i class="fa fa-search fa-fw"></i></span>
    	    						</div>
    	    						<p class="help-block"><strong>Silahkan ketik NIM dan klik tombol cari terlebih dahulu.</strong></p>
    	    					</div>
    	    					<div class="form-group">
    	    						<div class="input-group">
    	    							<span class="input-group-addon"><i class="fa fa-male fa-fw"></i></span>
    	    							<input type="text" id="nama" class="validate[required] form-control" placeholder="Nama Mahasiswa" readonly>
    	    						</div>
    	    					</div>
    	    					<div class="form-group">
    	    						<div class="input-group">
    	    							<span class="input-group-addon"><i class="fa fa-university fa-fw"></i></span>
    	    							<input type="text" id="prodi" class="validate[required] form-control" placeholder="Prodi Mahasiswa" readonly>
    	    						</div>
    	    					</div>
    	    					<div class="form-group">
    	    						<div class="input-group">
    	    							<span class="input-group-addon"><i class="fa fa-venus-mars fa-fw"></i></span>
    	    							<input type="text" id="jns_kelamin" class="validate[required] form-control" placeholder="Jenis Kelamin Mahasiswa" readonly>
    	    						</div>
    	    					</div>
    	    					<fieldset id="form" class="formPrivasi" disabled>
    	    						<div class="form-group">
    	    							<div class="input-group">
    	    								<span class="input-group-addon"><i class="fa fa-home fa-fw"></i></span>
    	    								<textarea class="validate[required] form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat Anggota" required></textarea>
    	    							</div>
    	    						</div>
    	    						<div class="formTelepon form-group">
    	    							<div class="input-group">
    	    								<span class="input-group-addon"><i class="fa fa-whatsapp fa-fw"></i></span>
    	    								<input type="text" name="telepon" id="telepon" class="validate[custom[number]] form-control" placeholder="Format : 628XXXXXXXXX" required>
    	    							</div>
    	    						</div>
    	    						<div class="formTelepon form-group">
    	    							<div class="input-group">
    	    								<span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
    	    								<input type="text" name="backup_telepon" id="backup_telepon" class=" form-control" placeholder="Format : 628XXXXXXXXX">
    	    							</div>
    	    						</div>
    	    						<div class="form-group">
    	    							<div class="input-group">
    	    								<span class="input-group-addon"><i class="fa fa-shirtsinbulk fa-fw"></i></span>
    	    								<select class="validate[required] form-control" name="ukuranbaju" id="ukuranbaju" required>
    	    									<option value="">-- Pilih Ukuran Baju --</option>
    	    									<option value="S">S</option>
    	    									<option value="M">M</option>
    	    									<option value="L">L</option>
    	    									<option value="XL">XL</option>
    	    									<option value="XXL">XXL</option>
    	    									<option value="XXXL">XXXL</option>
    	    									<option value="XXXXL">XXXXL</option>
    	    								</select>
    	    							</div>
    	    						</div>
    	    					</fieldset>
    	    					{{--<div class="form-group">
            					<div class="input-group">
            						<span class="input-group-addon"><i class="fa fa-ellipsis-h fa-fw"></i></span>
            						<select class="validate[required] form-control" name="peran" id="peran" disabled required>
            							<option value="">-- Pilih Peran --</option>
            							<option value="{{Crypt::encryptString('Anggota 1')}}">Anggota 1</option>
    	    					<option value="{{Crypt::encryptString('Anggota 2')}}">Anggota 2</option>
    	    					<option value="{{Crypt::encryptString('Anggota 3')}}">Anggota 3</option>
    	    					<option value="{{Crypt::encryptString('Anggota 4')}}">Anggota 4</option>
    	    					</select>
    	    				</div>
    	    		</div>--}}
    	    	</div>
    	    	<div class="modal-footer">
    	    		<button type="submit" id="simpan-anggota" class="btn btn-success">Simpan <i class="fa fa-save"></i></button>
    	    		<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    	    	</div>
    	    	</form>
    	    </div>
    	    </div>
    	    </div>
    	@endif
                            
    	<div class="modal fade" id="hapus-pkm" tabindex="-1" role="dialog" aria-labelledby="hapusPKMLabel" aria-hidden="true">
    		<div class="modal-dialog">
    			<div class="modal-content">
    				<form action="{{url('mhs/hapuspkm')}}" method="post">
    					@csrf
    					<div class="modal-header">
    						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    						<h3 class="modal-title" id="hapusPKMLabel">Hapus PKM</h3>
    					</div>
    					<div class="modal-body text-danger">
    						<h4>Anda yakin ingin menghapus PKM ini?</h4>
    					</div>
    					<div class="modal-footer">
    						<input type="hidden" name="kode_token" value="{{Crypt::encryptString($id)}}">
    						<button type="submit" class="btn btn-danger btn-mini">Hapus PKM</button>
    						<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>
                            
    	<div class="modal fade" id="hapus-anggota" tabindex="-1" role="dialog" aria-labelledby="hapusAnggotaLabel" aria-hidden="true">
    		<div class="modal-dialog">
    			<div class="modal-content">
    				<form action="{{url('mhs/hapusanggota')}}" method="post">
    					@csrf
    					<div class="modal-header">
    						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    						<h4 class="modal-title" id="hapusAnggotaLabel">Hapus Anggota</h4>
    					</div>
    					<div class="modal-body">
    						<p id="labelhapusanggota"></p>
    					</div>
    					<div class="modal-footer">
    						<input type="hidden" name="kode_data" id="kode_data">
    						<input type="hidden" name="kode_token" value="{{Crypt::encryptString($id)}}">
    						<button type="submit" class="btn btn-danger btn-mini">Hapus</button>
    						<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>
    @endif

    @if($saya->acc_anggota == "Y")
	    @if($pkm->file_proposal == "")
	        <div class="modal fade" id="upload-proposal" tabindex="-1" role="dialog" aria-labelledby="uploadProposalLabel" aria-hidden="true">
	        	<div class="modal-dialog">
	        		<div class="modal-content">
	        			<form action="{{url('mhs/uploadproposal')}}" class="form-validation" method="post" enctype="multipart/form-data">
	        				@csrf
	        				<div class="modal-header">
	        					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        					<h4 class="modal-title" id="uploadProposalLabel">Upload Proposal</h4>
	        				</div>
	        				<div class="modal-body">
	        					<div class="form-group">
	        						<div class="input-group">
	        							<span class="input-group-addon"><i class="fa fa-upload fa-fw"></i></span>
	        							<input type="file" name="file" class="validate[required] form-control" id="file" placeholder="File PKM">
	        						</div>
	        						<p class="help-block"><strong style="color: red">Format PDF Maks 5 MB</strong></p>
	        					</div>
	        				</div>
	        				<div class="modal-footer">
	        					<input type="hidden" name="kode_token" value="{{Crypt::encryptString($id)}}">
	        					<button type="submit" class="btn btn-success btn-mini">Simpan</button>
	        					<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
	        				</div>
	        			</form>
	        		</div>
	        	</div>
	        </div>
	    @endif

	    @if($pkm->file_proposal != "")
	        <div class="modal fade" id="revisi-proposal" tabindex="-1" role="dialog" aria-labelledby="revisiProposalLabel" aria-hidden="true">
	        	<div class="modal-dialog">
	        		<div class="modal-content">
	        			<form action="{{url('mhs/revisiproposal')}}" class="form-validation" method="post" enctype="multipart/form-data">
	        				@csrf
	        				<div class="modal-header">
	        					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        					<h4 class="modal-title" id="revisiProposalLabel">Revisi Proposal</h4>
	        				</div>
	        				<div class="modal-body">
	        					<div class="form-group">
	        						<div class="input-group">
	        							<span class="input-group-addon"><i class="fa fa-upload fa-fw"></i></span>
	        							<input type="file" id="file" name="file" class="validate[required] form-control" placeholder="File PKM" required>
	        						</div>
	        						<p class="help-block"><strong style="color: red">Format PDF Maks 5 MB</strong></p>
	        					</div>
	        				</div>
	        				<div class="modal-footer">
	        					<input type="hidden" name="kode_token" value="{{Crypt::encryptString($id)}}">
	        					<button type="submit" class="btn btn-success btn-mini">Simpan</button>
	        					<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
	        				</div>
	        			</form>
	        		</div>
	        	</div>
	        </div>
	    @endif

	    <div class="modal fade" id="edit-pkm" tabindex="-1" role="dialog" aria-labelledby="editPKMLabel" aria-hidden="true">
	    	<div class="modal-dialog">
	    		<div class="modal-content">
	    			<form action="{{url('mhs/editpkm')}}" class="form-validation" method="post">
	    				@csrf
	    				<div class="modal-header">
	    					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	    					<h4 class="modal-title" id="editPKMLabel">Edit Data PKM</h4>
	    				</div>
	    				<div class="modal-body">
	    					<div class="form-group">
	    						<div class="input-group">
	    							<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
	    							<input type="text" name="judul" class="validate[required] form-control" placeholder="Judul PKM" value="{{ $pkm->judul }}">
	    						</div>
	    					</div>
	    					<div class="form-group">
	    						<div class="input-group">
	    							<span class="input-group-addon"><i class="fa fa-list fa-fw"></i></span>
	    							<input type="text" name="keywords" class="validate[required] form-control" placeholder="Keyword PKM" value="{{ $pkm->keyword }}">
	    						</div>
	    					</div>
	    					@if ($pkm->id_tipe_pkm == "2" || $pkm->id_tipe_pkm == "3" || $pkm->id_tipe_pkm == "4")
	    					<div id="form-abstrak" class="form-group">
	    						<label>Abstrak</label>
	    						<div class="input-group">
	    							<span class="input-group-addon"><i class="fa fa-list fa-fw"></i></span>
	    							<textarea name="abstrak" class="validate[required] form-control" id="abstrak" cols="30">{{$pkm->abstrak}}</textarea>
	    						</div>
	    					</div>
	    					@endif
	    					@if($pkm->id_tipe_pkm == "3")
	    					<div id="form-linkurl" class="form-group">
	    						<label>Link URL</label>
	    						<div class="input-group">
	    							<input type="url" name="linkurl" id="linkurl" class="validate[required] form-control" placeholder="URL Video" value="{{$pkm->linkurl}}">
	    						</div>
	    					</div>
	    					@endif
	    					@if($pkm->id_tipe_pkm == "1")
	    					<div class="form-group">
	    						<label>Dana Pengajuan PKM</label>
	    						<div class="input-group">
	    							<span class="input-group-addon">Rp.</span>
	    							<input type="text" name="dana_pkm" id="danapkm" class="validate[required] form-control" placeholder="Contoh : 12.500.000" value="{{$pkm->dana_pkm}}">
	    						</div>
	    						<label class="text-danger" id="textDana"></label>
	    					</div>
	    					<div class="form-group" id="form-durasi">
	    						<label>Durasi Penelitian</label>
	    						<div class="input-group">
	    							<span class="input-group-addon"><i class="fa fa fa-calendar fa-fw"></i></span>
	    							<select class="validate[required] form-control" name="durasi">
	    								<option hidden value="{{$pkm->durasi}}">{{$pkm->durasi}} Bulan</option>
	    								<option value="1">1 Bulan</option>
	    								<option value="2">2 Bulan</option>
	    								<option value="3">3 Bulan</option>
	    								<option value="4">4 Bulan</option>
	    								<option value="5">5 Bulan</option>
	    							</select>
	    						</div>
	    					</div>
	    					@endif
	    					<div class="form-group">
	    						<label>Dosen Fakultas</label>
	    						<div class="input-group">
	    							<span class="input-group-addon"><i class="fa fa-university fa-fw"></i></span>
	    							<select class="validate[required] form-control" name="id_fakultas" id='id_fakultas'>
	    								<option value="">-- Nama Fakultas --</option>
	    								@foreach($list_fakultas as $fakultas)
	    								<option value="{{$fakultas->id }}">{{ $fakultas->nama_fakultas }}</option>
	    								@endforeach
	    							</select>
	    						</div>
	    					</div>
	    					<div class="form-group">
	    						<label>Nama Dosen</label>
	    						<div class="input-group">
	    							<span class="input-group-addon"><i class="fa fa-user-md fa-fw"></i></span>
	    							<select class="validate[required] form-control" name="nama_dosen" id='nama_dosen'>
	    								<option value="">-- Nama Dosen --</option>
	    								<option value="{{ $pkm->nama_dosen }}" selected>{{ $pkm->nama_dosen }}</option>
	    							</select>
	    						</div>
	    					</div>
	    					<div class="form-group">
	    						<label>NIDN/NIDK Dosen</label>
	    						<div class="input-group">
	    							<span class="input-group-addon"><i class="fa fa-thumb-tack fa-fw"></i></span>
	    							<input type="hidden" name="tipenidn" id="tipenidn">
	    							<input type="hidden" name="kodedosen" id="kodedosen" value="{{Crypt::encryptString($pkm->id_dosen)}}">
	    							@if($pkm->nidn_dosen == "" && $pkm->nidk_dosen == "")
	    							<input type="text" readonly name="nidn_dosen" id="nidn_dosen" class="disabled validate[required] form-control" placeholder="NIDN/NIDK Dosen Pendamping" value="{{ $pkm->nip_dosen }}">
	    							@else
	    							@if($pkm->nidn_dosen != "")
	    							<input type="text" readonly name="nidn_dosen" id="nidn_dosen" class="disabled validate[required] form-control" placeholder="NIDN/NIDK Dosen Pendamping" value="{{ $pkm->nidn_dosen }}">
	    							@else
	    							<input type="text" readonly name="nidn_dosen" id="nidn_dosen" class="disabled validate[required] form-control" placeholder="NIDN/NIDK Dosen Pendamping" value="{{ $pkm->nidk_dosen }}">
	    							@endif
	    							@endif
	    						</div>
	    					</div>
	    				</div>
	    				<div class="modal-footer">
	    					<input type="hidden" name="kode_token" value="{{Crypt::encryptString($id)}}">
	    					<button type="submit" id="saveEdit" class="btn btn-success btn-mini">Simpan</button>
	    					<button class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
	    				</div>
	    			</form>
	    		</div>
	    	</div>
	    </div>
    @endif

	<!-- Modal -->
	<div class="modal fade" id="modalNilai" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Nilai Proposal</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Judul PKM</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-male fa-fw"></i></span>
								<input type="text" class="validate[required] form-control" placeholder="Judul PKM" value="{{$pkm->judul}}" readonly>
								<input type="hidden" name="id" id="id_pkm">
							</div>
						</div>

						<div class="form-group">
							<label class="jenispkm-block">Skim PKM</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								<input type="text" id="skim_pkm" class="validate[required] form-control" value="{{$pkm->skim_lengkap}}" placeholder="Skim PKM" readonly>
							</div>
						</div>
						<hr>
						
						<div class="form-group">
							<label id="l1">L1</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								@if($pkm->proposal1 == 1)
								<input type="text" class="form-control" placeholder="L1" readonly value="Buruk">
								@elseif($pkm->proposal1 == 2)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Kurang">
								@elseif($pkm->proposal1 == 3)
								<input type="text" class="form-control" placeholder="L1" readonly value="Kurang">
								@elseif($pkm->proposal1 == 5)
								<input type="text" class="form-control" placeholder="L1" readonly value="Cukup">
								@elseif($pkm->proposal1 == 6)
								<input type="text" class="form-control" placeholder="L1" readonly value="Baik">
								@elseif($pkm->proposal1 == 7)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Baik">
								@endif
							</div>
						</div>

						<div class="form-group">
							<label id="l2">L2</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								@if($pkm->proposal2 == 1)
								<input type="text" class="form-control" placeholder="L1" readonly value="Buruk">
								@elseif($pkm->proposal2 == 2)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Kurang">
								@elseif($pkm->proposal2 == 3)
								<input type="text" class="form-control" placeholder="L1" readonly value="Kurang">
								@elseif($pkm->proposal2 == 5)
								<input type="text" class="form-control" placeholder="L1" readonly value="Cukup">
								@elseif($pkm->proposal2 == 6)
								<input type="text" class="form-control" placeholder="L1" readonly value="Baik">
								@elseif($pkm->proposal2 == 7)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Baik">
								@endif
							</div>
						</div>

						<div class="form-group">
							<label id="l3">L3</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								@if($pkm->proposal3 == 1)
								<input type="text" class="form-control" placeholder="L1" readonly value="Buruk">
								@elseif($pkm->proposal3 == 2)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Kurang">
								@elseif($pkm->proposal3 == 3)
								<input type="text" class="form-control" placeholder="L1" readonly value="Kurang">
								@elseif($pkm->proposal3 == 5)
								<input type="text" class="form-control" placeholder="L1" readonly value="Cukup">
								@elseif($pkm->proposal3 == 6)
								<input type="text" class="form-control" placeholder="L1" readonly value="Baik">
								@elseif($pkm->proposal3 == 7)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Baik">
								@endif
							</div>
						</div>

						<div class="form-group">
							<label id="l4">L4</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								@if($pkm->proposal4 == 1)
								<input type="text" class="form-control" placeholder="L1" readonly value="Buruk">
								@elseif($pkm->proposal4 == 2)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Kurang">
								@elseif($pkm->proposal4 == 3)
								<input type="text" class="form-control" placeholder="L1" readonly value="Kurang">
								@elseif($pkm->proposal4 == 5)
								<input type="text" class="form-control" placeholder="L1" readonly value="Cukup">
								@elseif($pkm->proposal4 == 6)
								<input type="text" class="form-control" placeholder="L1" readonly value="Baik">
								@elseif($pkm->proposal4 == 7)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Baik">
								@endif
							</div>
						</div>

						<div class="form-group" id="f5">
							<label id="l5">L5</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								@if($pkm->proposal5 == 1)
								<input type="text" class="form-control" placeholder="L1" readonly value="Buruk">
								@elseif($pkm->proposal5 == 2)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Kurang">
								@elseif($pkm->proposal5 == 3)
								<input type="text" class="form-control" placeholder="L1" readonly value="Kurang">
								@elseif($pkm->proposal5 == 5)
								<input type="text" class="form-control" placeholder="L1" readonly value="Cukup">
								@elseif($pkm->proposal5 == 6)
								<input type="text" class="form-control" placeholder="L1" readonly value="Baik">
								@elseif($pkm->proposal5 == 7)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Baik">
								@endif
							</div>
						</div>

						<div class="form-group" id="f6">
							<label id="l6">L6</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								@if($pkm->proposal6 == 1)
								<input type="text" class="form-control" placeholder="L1" readonly value="Buruk">
								@elseif($pkm->proposal6 == 2)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Kurang">
								@elseif($pkm->proposal6 == 3)
								<input type="text" class="form-control" placeholder="L1" readonly value="Kurang">
								@elseif($pkm->proposal6 == 5)
								<input type="text" class="form-control" placeholder="L1" readonly value="Cukup">
								@elseif($pkm->proposal6 == 6)
								<input type="text" class="form-control" placeholder="L1" readonly value="Baik">
								@elseif($pkm->proposal6 == 7)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Baik">
								@endif
							</div>
						</div>

						<div class="form-group" id="f7">
							<label id="l7">L7</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								@if($pkm->proposal7 == 1)
								<input type="text" class="form-control" placeholder="L1" readonly value="Buruk">
								@elseif($pkm->proposal7 == 2)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Kurang">
								@elseif($pkm->proposal7 == 3)
								<input type="text" class="form-control" placeholder="L1" readonly value="Kurang">
								@elseif($pkm->proposal7 == 5)
								<input type="text" class="form-control" placeholder="L1" readonly value="Cukup">
								@elseif($pkm->proposal7 == 6)
								<input type="text" class="form-control" placeholder="L1" readonly value="Baik">
								@elseif($pkm->proposal7 == 7)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Baik">
								@endif
							</div>
						</div>

						<div class="form-group" id="f8">
							<label id="l8">L8</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								@if($pkm->proposal8 == 1)
								<input type="text" class="form-control" placeholder="L1" readonly value="Buruk">
								@elseif($pkm->proposal8 == 2)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Kurang">
								@elseif($pkm->proposal8 == 3)
								<input type="text" class="form-control" placeholder="L1" readonly value="Kurang">
								@elseif($pkm->proposal8 == 5)
								<input type="text" class="form-control" placeholder="L1" readonly value="Cukup">
								@elseif($pkm->proposal8 == 6)
								<input type="text" class="form-control" placeholder="L1" readonly value="Baik">
								@elseif($pkm->proposal8 == 7)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Baik">
								@endif
							</div>
						</div>

						<div class="form-group" id="f9">
							<label id="l9">L9</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-book fa-fw"></i></span>
								@if($pkm->proposal9 == 1)
								<input type="text" class="form-control" placeholder="L1" readonly value="Buruk">
								@elseif($pkm->proposal9 == 2)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Kurang">
								@elseif($pkm->proposal9 == 3)
								<input type="text" class="form-control" placeholder="L1" readonly value="Kurang">
								@elseif($pkm->proposal9 == 5)
								<input type="text" class="form-control" placeholder="L1" readonly value="Cukup">
								@elseif($pkm->proposal9 == 6)
								<input type="text" class="form-control" placeholder="L1" readonly value="Baik">
								@elseif($pkm->proposal9 == 7)
								<input type="text" class="form-control" placeholder="L1" readonly value="Sangat Baik">
								@endif
							</div>
						</div>

						<div class="form-group">
							<label>Catatan Penilai</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-home fa-fw"></i></span>
								<textarea class="form-control" rows="15" placeholder="Catatan Penilai">{{$pkm->note_proposal}}</textarea>
							</div>

						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
					</div>
			</div>
		</div>
	</div>
	
	
	@if($pkm->status != "1" && $pkm->confirm_up == "")
	<!-- Konfirmasi-->
	<div class="modal fade" id="konfirUpload" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Konfirmasi</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">X</span>
						</button>
				</div>
				<div class="modal-body text-center">
					<h5><strong class="text-success">Selamat!! PKM Anda Dinyatakan Lolos Unggah Eksternal</strong></h5><br>
					Saya Sanggup untuk <strong>Melanjutkan PKM</strong> dan <strong>Berkomitmen</strong> Mengikuti Rangkaian Kegiatan PKM hingga akhir <br>
					<a href="https://bit.ly/pkmuny2020" class="text-danger" target="_blank">bit.ly/pkmuny2020</a>
				</div>
				<div class="modal-footer">
					<form action="{{url('mhs/confirm')}}" method="post">
						@csrf
						<input type="hidden" name="kode" value="{{Crypt::encryptString($id)}}">
						<input type="hidden" name="isi" value="{{Crypt::encryptString('Y')}}">
						<button type="submit" class="btn btn-sm btn-success">Bersedia</button>
					</form>
						<button type="button" class="btn btn-sm btn-default" class="close" data-dismiss="modal" aria-label="Close">Tutup</button>
				</div>
			</div>
		</div>
	</div>
	@endif
    @endsection






@section('footer')
    <script type="text/javascript" src="{{url('assets/bower_components/datedropper/js/datedropper.min.js')}}"></script>
	<script type="text/javascript" src="{{url('assets/js/jquery.validationEngine-en.js')}}"></script>
	<script type="text/javascript" src="{{url('assets/js/jquery.validationEngine.js')}}"></script>
	<script>
		(function($) {
			$.fn.checkFileType = function(options) {
				var defaults = {
					allowedExtensions: [],
					success: function() {},
					error: function() {}
				};
				options = $.extend(defaults, options);

				return this.each(function() {

					$(this).on('change', function() {
						var value = $(this).val(),
							file = value.toLowerCase(),
							extension = file.substring(file.lastIndexOf('.') + 1);

						if ($.inArray(extension, options.allowedExtensions) == -1) {
							options.error();
							$(this).focus();
						} else {
							options.success();

						}

					});

				});
			};

		})(jQuery);

		var uploadField = document.getElementById("file");
		uploadField.onchange = function() {
			if (this.files[0].size > 5055650) {
				new PNotify({
					title: 'File Oversize',
					text: 'Maaf, File Max 5MB ',
					type: 'error'
				});
				console.log("file size = " + this.files[0].size + "/5000050")
				this.value = "";
			};
		};

		$(function() {
			$('#file').checkFileType({
				allowedExtensions: ['pdf'],
				error: function() {
					new PNotify({
						title: 'File not PDF',
						text: 'Maaf, hanya type pdf yang diupload ',
						type: 'error'
					});
					document.getElementById("file").value = "";
				}
			});
		});
	</script>

	@if($pkm->status != "1" && $pkm->confirm_up == "")
	<script>
		$(document).ready(function() {
			$('#konfirUpload').modal('show');
		});
	</script>
	@endif

	<script>
	 	$(document).ready(function() {

				var skim = "{{$pkm->skim_singkat}}";
				var f5 = $('#f5');
				var f6 = $('#f6');
				var f7 = $('#f7');
				var f8 = $('#f8');
				var f9 = $('#f9');

				var l1 = $('#l1');
				var l2 = $('#l2');
				var l3 = $('#l3');
				var l4 = $('#l4');
				var l5 = $('#l5');
				var l6 = $('#l6');
				var l7 = $('#l7');
				var l8 = $('#l8');
				var l9 = $('#l9');
				
				if (skim == "PKM-PSH" || skim == "PKM-PE") {
					l1.html('1. Gagasan (orisinalitas, unik, dan bermanfaat)');
					l2.html('<br>2. Perumusan masalah (fokus dan Atraktif)');
					l3.html('<br>3. Tinjauan Pustaka (state of the art)');
					l4.html('<br>4. Kesesuaian dan Kemutahiran Metode Penelitian');
					l5.html('<br>5. Kontribusi Perkembangan Ilmu dan Teknologi');
					l6.html('<br>6. Potensi Publikasi Artikel Ilmiah/HKI');
					l7.html('<br>7. Kemanfaatan');
					l8.html('<br>8. Jadwal dan Waktu Lengkap serta Jelas');
					l9.html('<br>9. Penyusunan Anggaran Biaya Lengkap, Rinci, Wajar, dan Jelas Peruntukannya');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.removeAttr('hidden');
					f9.removeAttr('hidden');

				} else if (skim == "PKM-K") {
					l1.html('1. Gagasan (unik, dan bermanfaat)');
					l2.html('<br>2. Keunggulan Produk/Jasa');
					l3.html('<br>3. Peluang Pasar');
					l4.html('<br>4. Potensi Perolehan Profit');
					l5.html('<br>5. Potensi Keberlanjutan Usaha');
					l6.html('<br>6. Jadwal dan Waktu Lengkap serta Jelas');
					l7.html('<br>7. Penyusunan Anggaran Biaya Lengkap, Rinci, Wajar, dan Jelas Peruntukannya');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

				} else if (skim == "PKM-KC") {
					l1.html('1. Gagasan (Orisinalitas, Unik, dan Manfaat Masa Depan)');
					l2.html('<br>2. Kemutakhiran IPTEK yang Diadopsi');
					l3.html('<br>3. Kesesuaian Metode Pelaksanaan');
					l4.html('<br>4. Kontribusi Produk Luaran terhadap Perkembangan IPTEK');
					l5.html('<br>5. Potensi Publikasi Artikel Ilmiah/HKI');
					l6.html('<br>6. Jadwal dan Waktu Lengkap serta Jelas');
					l7.html('<br>7. Penyusunan Anggaran Biaya Lengkap, Rinci, Wajar, dan Jelas Peruntukannya');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

				} else if (skim == "PKM-M") {
					l1.html('1. Perumusan Masalah');
					l2.html('<br>2. Ketepatan Solusi (Fokus dan Atraktif)');
					l3.html('<br>3. Ketepatan Masyarakat Sasaran');
					l4.html('<br>4. Nilai Tambah untuk Masyarakat Sasaran');
					l5.html('<br>5. Keberlanjutan Program');
					l6.html('<br>6. Penjadwalan Kegiatan dan Personalia:Lengkap, Jelas, Waktu dan Personalianya Sesuai');
					l7.html('<br>7. Penyusaunan Anggaran Biaya:Lengkap, Rinci, Wajar dan Jelas Peruntukannya');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

				} else if (skim == "PKM-T") {
					l1.html('1. Kemutakhiran IPTEK yang diadopsi');
					l2.html('<br>2. Ketepatan Solusi (Fokus dan Atraktif)');
					l3.html('<br>3. Komitmen Kemitraan');
					l4.html('<br>4. Nilai Tambah bagi Mitra');
					l5.html('<br>5. Potensi Paten/HKI');
					l6.html('<br>6. Penjadwalan Kegiatan dan Personalia:Jadwal dan Waktu Lengkap serta Jelas');
					l7.html('<br>7. Penyusaunan Anggaran Biaya:Lengkap, Rinci, Wajar dan Jelas Peruntukannya');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

				} else if (skim == "PKM-GT") {
					l1.html('1. Format Tulis: </br> Ukuran kertas, tipografi, kerapihan ketik, tata letak, jumlah halaman </br> Penggunaan Bahasa Indonesia yang baik dan benar </br> Kesesuaian dengan format penulisan yang tercantum di Pedoman');
					l2.html('<br>2. Gagasan: </br> Kreativitas Gagasan </br> Kelayakan Implementasi');
					l3.html('<br>3. Sumber Informasi: </br> Kesesuaian sumber informasi dengan gagasan yang ditawarkan </br> Akurasi dan aktualisasi informasi');
					l4.html('<br>4. Kesimpulan: </br> Prediksi hasil implementasi gagasan');

					f5.attr('hidden', 'hidden');
					f6.attr('hidden', 'hidden');
					f7.attr('hidden', 'hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');

				} else if (skim == "PKM-AI") {
					l1.html('1. Judul: Kesesuaian isi dan judul artikel');
					l2.html('<br>2. Abstrak: Latar Belakang, Tujuan, Metode, Hasil, Kesimpulan, Kata Kunci');
					l3.html('<br>3. Pendahuluan: Persoalan yang mendasari pelaksanaan uraian dasar-dasar keilmuan yang mendukung kemutakhiran substansi pekerjaan');
					l4.html('<br>4. Tujuan: Menemukan teknik/konsep/metode sebagai jawaban atas persoalan');
					l5.html('<br>5. Metode: Kesesuaian dengan yang akan diselesaikan, pengembangan metode baru, penggunaan metode yang sudah ada');
					l6.html('<br>6. Hasil dan Pembahasan: Kumpulan dan kejelasan penampilan data proses/teknik pengolahan data, ketajaman analisis dan sintesis data, perbandingan hasil dengan hipotesis atau hasil sejenis sebelumnya');
					l7.html('<br>7. Kesimpulan: Tingkat ketercapaian hasil dengan tujuan');
					l8.html('<br>8. Daftar Pustaka: Ditulis dengan sistem havard (nama,tahun), sesuai dengan uraian sitasi, kemutakhiran pustaka');

					f5.removeAttr('hidden');
					f6.removeAttr('hidden');
					f7.removeAttr('hidden');
					f8.removeAttr('hidden');
					f9.attr('hidden', 'hidden');

				} else if (skim == "PKM-GFK") {
					l1.html('1. Sistematika dan Kejelasan Alur Pikir');
					l2.html('<br>2. Penguasaan topik dan kreativitas solusi yang diajukan');
					l3.html('<br>3. Dinamika dan Kualitas Visualisasi Konten');
					l4.html('<br>4. Durasi maksimal 10 menit');

					f5.attr('hidden', 'hidden');
					f6.attr('hidden', 'hidden');
					f7.attr('hidden', 'hidden');
					f8.attr('hidden', 'hidden');
					f9.attr('hidden', 'hidden');
				}	
		});
	</script>
    
    <script>
		$(document).ready(function() {
			jQuery('.form-validation').validationEngine();
		});

		$('.tambahAnggota').click(function() {
			$('#simpan-anggota').attr('disabled', 'disabled');
			$('.formPrivasi').show();
			$('#tambah-anggota').modal('show');
		});
    </script>
    <script>
    $('.uploadProposal').click(function() {
			/*var notice = new PNotify({
						     title: 'Checking..',
						     text: 'Check Sudah Download template PKM',
						     icon: 'icon-spinner4 spinner',
						     type: 'warning'
			//			 });
			var kode = $(this).data('kode');
			$.get("{{ URL::to('/mhs/cekdownlempeng') }}", {kode: kode})
			.done(function(data) {
				if ($.isNumeric(data)) {
					$('#upload-proposal').modal('show');
				} else if(data == "notdownload"){
					new PNotify({
					    title: 'Denied!',
					    text: 'Anda belum download template PKM.',
					    icon: 'icofont icofont-info-circle',
					    type: 'warning'
					});
				} else if(data == "notacc"){
					new PNotify({
					    title: 'Denied!',
					    text: 'Anggota Belum Menerima PKM Anda.',
					    icon: 'icofont icofont-info-circle',
					    type: 'warning'
					});
				}
			})
			.fail(function() {
				new PNotify({
					title: 'Something Like Wrong',
					text: 'Server Error silakan Refresh halaman anda',
					type: 'error'
				});
			});*/
			$('#upload-proposal').modal('show');
		});
    </script>
    <script>
        $('#danapkm').on('keyup', function() {
			var input = $(this).val();
			var min = 5000000;
			var max = 12500000;

			if (input <= max && input >= min) {
				//$('#text_akhir').html('Periksa kembali dan pastikan semua terisi dengan benar sesuai pedoman');
				$('#textDana').html('');
				$('#textDana').hide();
				$('#saveEdit').removeAttr('disabled');
			} else {
				$('#textDana').show();
				$('#textDana').html('Dana yang diajukan minimal Rp.' + min + ',- dan maksimal Rp.' + max + ',-');
				//$('#text_akhir').html('Maaf dana pkm anda tidak sesuai dengan pedoman');
				$('#saveEdit').attr('disabled', 'disabled');
			};
		});

		$('.templateBtn').click(function() {
			@if($modetemplate -> konten == "1")
			new PNotify({
				title: 'Silakan Tunggu..',
				text: 'Persiapan Download Tempalte',
				icon: 'icon-spinner4 spinner',
				type: 'warning'
			});
			@else
			new PNotify({
				title: 'Template Deactivated',
				text: 'Maaf.. Download template sedang dinonaktifkan, silakan hubungi TIM PKM Center Fakultas Anda',
				icon: 'icon-spinner4 spinner',
				type: 'error'
			});
			@endif
		});

		$('.revisiProposal').click(function() {
			$('#revisi-proposal').modal('show');
		});

		$('.editPKM').click(function() {
			var fak = "{{$dosen->id_fakultas}}";
			$('#id_fakultas').val(fak);
			$('#textDana').hide();
			$('#edit-pkm').modal('show');
		});
		$('.hapusPKM').click(function() {
			$('#hapus-pkm').modal('show');
		});

		$('#tambah-anggota').on('hidden.bs.modal', function() {
			$(this).find('form').trigger('reset');
			$('.help-block').html('<strong>Silahkan ketik NIM dan klik tombol cari terlebih dahulu.</strong>');
			$('#nim').removeAttr('readonly');
			$('#form').attr('disabled', 'disabled');
			$('.formPrivasi').show();
			$('#peran').attr('disabled', 'disabled');
			$('#simpan-anggota').attr('disabled', 'disabled');
		});

		$('.hapusAnggota').click(function() {
			$nama = $(this).data('namaanggota');
			$('#labelhapusanggota').html('Apakah anda ingin hapus <strong class="text-danger">' + $nama + '</strong> dari pkm anda?');
			$('#kode_data').val($(this).data('kodedata'));
			$('#hapus-anggota').modal('show');
		});

		$('#search').click(function() {
			var nim = $('#nim').val();
			var kode_token = "{{Crypt::encryptString($id)}}";
			var tipe_kode = "{{Crypt::encryptString($pkm->id_tipe_pkm)}}"
			if (nim != '') {
				var input_icon = $(this).parent().children().first().children();
				var input_help = $(this).parent().parent().children().last();

				input_icon.removeClass('fa-user').addClass('fa-spinner fa-spin');
				input_help.hide();

				$.get("{{ URL::to('/mhs/cek-mhs') }}", {
						nim: nim,
						tipe_kode: tipe_kode,
						kode_token: kode_token
					})
					.done(function(result) {
						input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
						if ($.isEmptyObject(result)) {
							new PNotify({
								title: 'NIM Not Founded!',
								text: 'Mahasiswa tidak terdaftar.',
								icon: 'icofont icofont-info-circle',
								type: 'error'
							});
							input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
							input_help.children().html('Silahkan ketik NIM dan klik tombol cari terlebih dahulu.');
							input_help.show();
							$('#peran').attr('disabled', 'disabled');
							$('#simpan-anggota').attr('disabled', 'disabled');
						} else {
							if ($.isNumeric(result)) {
								new PNotify({
									title: 'Gagal Tambah Anggota!',
									text: 'Anggota sudah mengikuti 2 kelompok.',
									icon: 'icofont icofont-info-circle',
									type: 'error'
								});
								input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
								input_help.children().html('Silahkan ketik NIM dan klik tombol cari terlebih dahulu.');
								input_help.show();
								$('#peran').attr('disabled', 'disabled');
								$('#simpan-anggota').attr('disabled', 'disabled');
							} else if ((result.isi.kelengkapan) == "Y" || (result.isi.kelengkapan) == "N") {
								input_help.children().html('Mahasiswa sudah terdaftar silahkan langsung pilih peran dan tombol simpan');
								input_help.show();
								$('#nim').attr('readonly', 'readonly');
								$('#form').attr('disabled', 'disabled');
								$('#kode').val(result.kode);
								$('#nama').val(result.isi.nama);
								$('#prodi').val(result.isi.nama_prodi + ' - ' + result.isi.jenjang_prodi);
								$('.formPrivasi').hide();
								$('.formTelepon').hide();
								if ((result.isi.jenis_kelamin) == "P") {
									$('#jns_kelamin').val("Perempuan");
								} else {
									$('#jns_kelamin').val("Laki-laki");
								}
								$('#peran').removeAttr('disabled');
								$('#simpan-anggota').removeAttr('disabled');
							} else {
								input_help.children().html('Mahasiswa ditemukan silahkan lengkapi data');
								input_help.show();
								$('#nim').attr('readonly', 'readonly');
								$('#form').removeAttr('disabled');
								$('.formTelepon').show();
								$('.formPrivasi').show();
								$('#kode').val(result.kode);
								$('#nama').val(result.isi.nama_mahasiswa);
								$('#prodi').val(result.isi.nama_prodi + ' - ' + result.isi.jenjang_prodi);
								if ((result.isi.jns_kel_mahasiswa) == "P") {
									$('#jns_kelamin').val("Perempuan");
								} else {
									$('#jns_kelamin').val("Laki-laki");
								}
								$('#peran').removeAttr('disabled');
								$('#simpan-anggota').removeAttr('disabled');
							};
						}
					})
					.fail(function() {
						toastr.error('Kesalahan server! Silahkan reload halaman dan coba lagi');
						input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user');
						input_help.children().html('Silahkan ketik NIM dan klik tombol cari terlebih dahulu.');
						input_help.show();
					});
			}
		});

		$('#fakultas').on('change', function() {
			$('#prodi').html('<option value="">-- Pilih Prodi --</option>');
			var fakultas = $(this).val();
			var input_icon = $('#prodi').parent().children().first().children();
			var input_help = $('#prodi').parent().parent().children().last();

			input_icon.removeClass('fa-university').addClass('fa-spinner fa-spin');
			input_help.hide();

			$.get("{{ URL::to('/prodi') }}", {
					fakultas: fakultas
				})
				.done(function(result) {
					input_icon.removeClass('fa-spinner fa-spin').addClass('fa-university');
					if ($.isEmptyObject(result)) {
						input_help.show();
					} else {
						$.each(result, function(index, value) {
							$('#prodi').append('<option value="' + value.id + '">' + value.nama_prodi + '</option>');
						});
					};
				})
				.fail(function() {
					toastr.error('Kesalahan server! Silahkan ganti fakultas ke pilihan lain dan pilih lagi fakultas anda');
					input_icon.removeClass('fa-spinner fa-spin').addClass('fa-university');
					input_help.children().html('Silahkan pilih fakultas terlebih dahulu.');
					input_help.show();
				});
		});

		$('#nama_dosen').on('change', function() {
			var input_icon = $('#nidn_dosen').parent().children().first().children();
			input_icon.removeClass('fa-thumb-tack').addClass('fa-spinner fa-spin');
			var nama_dosen = $(this).val();
			var id_fakultas = $('#id_fakultas').val();
			$.get("{{ URL::to('/mhs/cek-dosen') }}", {
					nama_dosen: nama_dosen,
					id_fakultas: id_fakultas
				})
				.done(function(result) {
					if ($.isEmptyObject(result.dosen.nidn_dosen) && $.isEmptyObject(result.dosen.nidk_dosen)) {
						input_icon.removeClass('fa-spinner fa-spin').addClass('fa-thumb-tack');
						$('#nidn_dosen').val(result.dosen.nip_dosen);
        		    	$('#kodedosen').val(result.kode);
        		    	$('#tipenidn').val('NIP');
                    	new PNotify({
				    		title: 'NIDN/NIDK Not Found!',
				    		text: 'Dosen tersebut Menggunakan NIP',
				    		icon: 'icofont icofont-info-circle',
				    		type: 'danger'
				    	});
					} else {
						if ($.isEmptyObject(result.dosen.nidn_dosen)) {
							input_icon.removeClass('fa-spinner fa-spin').addClass('fa-thumb-tack');
							$('#nidn_dosen').val(result.dosen.nidk_dosen);
							$('#kodedosen').val(result.kode);
							$('#tipenidn').val('NIDK');
							new PNotify({
								title: 'NIDK Only',
								text: 'Dosen masih menggunakan NIDK, atau hubungi PKM Center untuk memperbarui NIDN',
								icon: 'icofont icofont-info-circle',
								type: 'warning'
							});
						} else {
							input_icon.removeClass('fa-spinner fa-spin').addClass('fa-thumb-tack');
							$('#nidn_dosen').val(result.dosen.nidn_dosen);
							$('#kodedosen').val(result.kode);
							$('#tipenidn').val('NIDN');
						}
					}
				})
				.fail(function() {
					input_icon.removeClass('fa-spinner fa-spin').addClass('fa-thumb-tack');
					toastr.error('Kesalahan server! Nidn tidak valid');
				});
		});


		$('#id_fakultas').on('change', function() {
			$('#nama_dosen').html('<option value="">-- Nama Dosen --</option>');
			var id_fakultas = $(this).val();
			var tipepkm = "{{Crypt::encryptString($pkm->id_tipe_pkm)}}";
			var input_icon = $('#nama_dosen').parent().children().first().children();

			input_icon.removeClass('fa-user-md').addClass('fa-spinner fa-spin');

			$.get("{{ URL::to('/mhs/dosen-fakultas') }}", {
					id_fakultas: id_fakultas,
					tipepkm: tipepkm
				})
				.done(function(result) {

					if ($.isEmptyObject(result)) {
						input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user-md');
						input_help.show();
						new PNotify({
							title: 'Dosen Not Founded!',
							text: 'Dosen tidak ditemukan.',
							icon: 'icofont icofont-info-circle',
							type: 'error'
						});
					} else {
						input_icon.removeClass('fa-spinner fa-spin').addClass('fa-user-md');
						$.each(result, function(index, value) {
							$('#nama_dosen').append('<option value="' + value.nama_dosen + '">' + value.nama_dosen + '</option>');
						});
					};
				})
				.fail(function(result) {
					toastr.error('Kesalahan server! Hasil tidak valid');
				});
		});

		$('#edit-pkm').on('hidden.bs.modal', function() {
			$(this).find('form').trigger('reset');
		});

		$('.aktifkanAnggota').click(function() {
			var code = "{{ csrf_token() }}";
			var tipekode = "{{Crypt::encryptString($pkm->id_file_pkm)}}";
			var kode = $(this).data('kode');
			swal({
				title: "Terima?",
				text: "Apakah anda yakin menerima sebagai Anggota PKM ini?",
				type: "warning",
				showCancelButton: true,
				showLoaderOnConfirm: true,
				confirmButtonText: "Terima ?",
				closeOnConfirm: false
			}, function() {
				$.ajax({
					url: "{{url('mhs/accanggota')}}",
					type: "POST",
					data: {
						_token: code,
						tipekode: tipekode,
						kode: kode
					},
					success: function() {
						swal("Diterima!", "Anda menerima PKM ini", "success");
						setTimeout(function() {
							location.reload();
						}, 1500);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						swal("Gagal Menerima!", "Silakan Coba Lagi", "error");
					}
				});
			});
		});
    </script>
@endsection
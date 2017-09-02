using JavCrawl.Dal.Context;
using JavCrawl.Utility.Context;
using System;
using System.Threading.Tasks;

namespace JavCrawl.Utility
{
    public class JobAuto
    {
        private readonly IDbRepository _dbRepository;
        private readonly IFilmsUploadRepository _filmuploadRepository;
        private readonly IOpenloadHelper _openloadHelper;
        private readonly IBitPornoHelper _bitPornoHelper;
        private readonly IStreamCherryHelper _streamCherryHelper;

        public JobAuto(IOpenloadHelper openloadHelper,
            IBitPornoHelper bitPornoHelper,
            IDbRepository dbRepository,
            IStreamCherryHelper streamCherryHelper,
            IFilmsUploadRepository filmuploadRepository)
        {
            _dbRepository = dbRepository;
            _openloadHelper = openloadHelper;
            _bitPornoHelper = bitPornoHelper;
            _streamCherryHelper = streamCherryHelper;
            _filmuploadRepository = filmuploadRepository;
        }
        public void ExecuteCrawl()
        {
            try
            {
                AsyncHelper.RunSync(() => _dbRepository.RunJobCrawl());
            }
            catch
            {

            }
        }

        public void ExecuteRemoteUpload()
        {
            try
            {
                AsyncHelper.RunSync(() => RemoteUpload());
            }
            catch
            {

            }
        }

        public void ExecuteGenarateSlideAndFilmMember()
        {
            try
            {
                AsyncHelper.RunSync(() => _dbRepository.JobUpdateSlideAndFilmMember());
            }
            catch
            {

            }
        }


        private async Task RemoteUpload()
        {
            try
            {
                var uploading = _filmuploadRepository.GetFilmsUploading();
                foreach(var item in uploading)
                {
                    var link = string.Empty;
                    if (item.Server == Models.DbEntity.ServerUpload.BitPorno)
                    {
                        link = await _bitPornoHelper.RemoteFileStatus(item.RemoteId);
                    }
                    else if (item.Server == Models.DbEntity.ServerUpload.OpenLoad)
                    {
                        link = await _openloadHelper.RemoteFileStatus(item.RemoteId, item.FilmId);
                    }
                    else if (item.Server == Models.DbEntity.ServerUpload.StreamCherry)
                    {
                        link = await _streamCherryHelper.RemoteFileStatus(item.RemoteId, item.FilmId);
                    }

                    if (string.IsNullOrWhiteSpace(link)) continue;

                    if (link == "error")
                        await _filmuploadRepository.UpdateUploadError(item.Id);
                    else 
                        await _filmuploadRepository.UpdateUploaded(item.Id, link);
                }

                var needUpload = _filmuploadRepository.GetFilmNeedUpload();
                
                if (needUpload != null)
                {
                    var directLink = await _filmuploadRepository.GetLinkDownload(needUpload);

                    if (string.IsNullOrWhiteSpace(directLink)) return;

                    //BitPorno
                     var remoteId = await _bitPornoHelper.RemoteFile(directLink);
                    if (string.IsNullOrWhiteSpace(remoteId)) await _filmuploadRepository.UpdateUploadError(needUpload.Id, Models.DbEntity.ServerUpload.BitPorno);
                    else await _filmuploadRepository.UpdateUploading(needUpload.Id, Models.DbEntity.ServerUpload.BitPorno, remoteId);

                    //Openload
                    var olExists = await _filmuploadRepository.CheckExistsOpenload(needUpload.Id);
                    if (!olExists)
                    {
                        remoteId = await _openloadHelper.RemoteFile(directLink);
                        if (string.IsNullOrWhiteSpace(remoteId)) await _filmuploadRepository.UpdateUploadError(needUpload.Id, Models.DbEntity.ServerUpload.OpenLoad);
                        else await _filmuploadRepository.UpdateUploading(needUpload.Id, Models.DbEntity.ServerUpload.OpenLoad, remoteId);
                    }

                    //StreamCherry
                    remoteId = await _streamCherryHelper.RemoteFile(directLink);
                    if (string.IsNullOrWhiteSpace(remoteId)) await _filmuploadRepository.UpdateUploadError(needUpload.Id, Models.DbEntity.ServerUpload.StreamCherry);
                    else await _filmuploadRepository.UpdateUploading(needUpload.Id, Models.DbEntity.ServerUpload.StreamCherry, remoteId);

                }
            }
            catch
            {
            }
        }
    }
}

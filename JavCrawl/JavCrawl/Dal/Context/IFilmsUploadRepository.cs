using JavCrawl.Models.DbEntity;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace JavCrawl.Dal.Context
{
    public interface IFilmsUploadRepository
    {
        IList<FilmsUpload> GetFilmsUploading();

        Films GetFilmNeedUpload();

        Task<bool> UpdateUploading(int filmId, ServerUpload server, string remoteId);

        Task<bool> UpdateUploaded(int uploadId, string link);

        Task<bool> UpdateUploadError(int filmId, ServerUpload server);

        Task<bool> CheckExistsOpenload(int filmId);

        Task<string> GetLinkDownload(Films film);

        Task<bool> UpdateUploadError(int uploadId);
    }
}

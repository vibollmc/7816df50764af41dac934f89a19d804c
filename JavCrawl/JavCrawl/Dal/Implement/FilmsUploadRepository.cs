using JavCrawl.Dal.Context;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using JavCrawl.Models.DbEntity;
using JavCrawl.Utility.Context;

namespace JavCrawl.Dal.Implement
{
    public class FilmsUploadRepository : IFilmsUploadRepository
    {
        private readonly MySqlContext _dbContext;
        private readonly IHtmlHelper _htmlHelper;

        public FilmsUploadRepository(MySqlContext dbContext, IHtmlHelper htmlHelper)
        {
            _dbContext = dbContext;
            _htmlHelper = htmlHelper;
        }

        public Films GetFilmNeedUpload()
        {
            var filmsUploaded = _dbContext.FilmsUpload.Select(z => z.FilmId);
            if (filmsUploaded != null && filmsUploaded.Count() > 0)
                return _dbContext.Films.OrderByDescending(x => x.Order).FirstOrDefault(x => !filmsUploaded.Contains(x.Id) && x.Extend != null && x.Extend != "buz" && x.CustomerId == null);
            else return _dbContext.Films.OrderByDescending(x => x.Order).FirstOrDefault(x => x.Extend != null && x.Extend != "buz" && x.CustomerId == null);
        }

        public IList<FilmsUpload> GetFilmsUploading()
        {
            return _dbContext.FilmsUpload.Where(x => x.Status == StatusUpload.Uploading).ToList();
        }

        public async Task<bool> UpdateUploaded(int uploadId, string link)
        {
            using(var dbTransaction = await _dbContext.Database.BeginTransactionAsync())
            {
                try
                {
                    var upload = _dbContext.FilmsUpload.FirstOrDefault(x => x.Id == uploadId);
                    if (upload == null) return true;

                    upload.Status = StatusUpload.Done;
                    upload.UpdatedAt = DateTime.Now;
                    upload.RemoteId = link;

                    var serverName = string.Empty;
                    var server = string.Empty;

                    if (upload.Server == ServerUpload.BitPorno)
                    {
                        server = "bitporno";
                        serverName = "Server BP";
                    }
                    else if(upload.Server == ServerUpload.OpenLoad)
                    {
                        server = "openload";
                        serverName = "Server OL";
                    }
                    else if (upload.Server == ServerUpload.StreamCherry)
                    {
                        server = "streamcherry";
                        serverName = "Server SC";
                    }
                    else
                    {
                        upload.Status = StatusUpload.Error;
                        return false;
                    }

                    await _dbContext.SaveChangesAsync();

                    var eps = _dbContext.Episodes.FirstOrDefault(x => x.FilmId == upload.FilmId && x.FileName.Contains(server));

                    if (eps == null)
                    {
                        eps = new Episodes
                        {
                            FileName = link,
                            FilmId = upload.FilmId,
                            Title = serverName,
                            Type = "Full",
                            Viewed = 0,
                            CreatedAt = DateTime.Now
                        };

                        _dbContext.Episodes.Add(eps);
                    }
                    else
                    {
                        eps.FileName = link;
                        eps.UpdatedAt = DateTime.Now;
                    }

                    await _dbContext.SaveChangesAsync();

                    dbTransaction.Commit();
                }
                catch
                {
                    dbTransaction.Rollback();
                }
            }

            return true;
        }

        public async Task<bool> UpdateUploading(int filmId, ServerUpload server, string remoteId)
        {
            await _dbContext.FilmsUpload.AddAsync(new FilmsUpload
            {
                CreatedAt = DateTime.Now,
                Server = server,
                RemoteId = remoteId,
                FilmId = filmId,
                Status = StatusUpload.Uploading
            });

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public async Task<bool> UpdateUploadError(int uploadId)
        {
            var upload = _dbContext.FilmsUpload.FirstOrDefault(x => x.Id == uploadId);
            if (upload == null) return true;

            upload.Status = StatusUpload.Error;

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public async Task<bool> UpdateUploadError(int filmId, ServerUpload server)
        {
            await _dbContext.FilmsUpload.AddAsync(new FilmsUpload
            {
                CreatedAt = DateTime.Now,
                Server = server,
                FilmId = filmId,
                Status = StatusUpload.Error
            });

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public async Task<bool> CheckExistsOpenload(int filmId)
        {
            var eps = _dbContext.Episodes.FirstOrDefault(x => x.FileName.Contains("openload") && x.FileName.Contains("javmile.com"));
            if (eps == null) return false;

            await _dbContext.FilmsUpload.AddAsync(new FilmsUpload
            {
                CreatedAt = DateTime.Now,
                FilmId = filmId,
                RemoteId = eps.FileName,
                Server = ServerUpload.OpenLoad,
                UpdatedAt = DateTime.Now,
                Status = StatusUpload.Done
            });

            await _dbContext.SaveChangesAsync();

            return true;
        }

        public async Task<string> GetLinkDownload(Films film)
        {
            var eps = _dbContext.Episodes.FirstOrDefault(x => x.FilmId == film.Id &&
                    (x.FileName.Contains("javhihi.com") || x.FileName.Contains("jav789.com") || x.FileName.Contains("javbuz.com")));

            var linkFilm = eps == null ? string.Format("http://jav{0}.com/movie/{1}.html", film.Extend, film.Slug) : eps.FileName;
            
            try
            {
                var result = await _htmlHelper.GetRedirectLinkVideo(linkFilm);

                if (result == null || result.Count == 0) {
                    await MarkLinkDownloadAsError(film.Id);

                    return null;
                }
                return result.FirstOrDefault().File;
            }
            catch {
                await MarkLinkDownloadAsError(film.Id);

                return null;
            }
        }

        private async Task<bool> MarkLinkDownloadAsError(int filmId)
        {
            var film = _dbContext.Films.FirstOrDefault(x => x.Id == filmId);
            if (film == null) return false;

            film.CustomerId = 0;

            await _dbContext.SaveChangesAsync();

            return true;
        }
    }
}

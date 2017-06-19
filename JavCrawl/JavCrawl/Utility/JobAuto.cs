using JavCrawl.Dal.Context;
using JavCrawl.Utility.Context;

namespace JavCrawl.Utility
{
    public class JobAuto
    {
        private readonly IDbRepository _dbRepository;
        private readonly IOpenloadHelper _openloadHelper;

        public JobAuto(IDbRepository dbRepository, IOpenloadHelper openloadHelper)
        {
            _dbRepository = dbRepository;
            _openloadHelper = openloadHelper;
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

        public void ExecuteOpenloadRemote()
        {
            try
            {
                AsyncHelper.RunSync(() => _openloadHelper.JobRemoteFile());
            }
            catch
            {

            }
        }
    }
}

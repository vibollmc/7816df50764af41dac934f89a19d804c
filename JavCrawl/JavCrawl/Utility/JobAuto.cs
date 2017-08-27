using JavCrawl.Dal.Context;
using JavCrawl.Utility.Context;

namespace JavCrawl.Utility
{
    public class JobAuto
    {
        private readonly IDbRepository _dbRepository;
        private readonly IOpenloadHelper _openloadHelper;
        private readonly IBitPornoHelper _bitPornoHelper;

        public JobAuto(IOpenloadHelper openloadHelper,
            IBitPornoHelper bitPornoHelper,
            IDbRepository dbRepository)
        {
            _dbRepository = dbRepository;
            _openloadHelper = openloadHelper;
            _bitPornoHelper = bitPornoHelper;
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

        public void ExecuteBitPornoRemote()
        {
            try
            {
                AsyncHelper.RunSync(() => _bitPornoHelper.JobRemoteFile());
            }
            catch
            {

            }
        }
    }
}

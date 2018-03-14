using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Entities;

namespace HighLights.Web.Dal.Context
{
    public interface IImageServerRepository
    {
        Task<ImageServer> GetActiveImageServer();
    }
}

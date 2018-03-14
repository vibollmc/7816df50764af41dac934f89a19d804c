using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Utilities.Model;

namespace HighLights.Web.Utilities.Context
{
    public interface ICrawler
    {
        Task Run();
    }
}

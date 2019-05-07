using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Entities;

namespace Football.Show.Dal.Context
{
    public interface ITagRepository
    {
        Task<IList<ViewModels.XmlModel>> GetXmlTags();
        Task<IEnumerable<Tag>> GetTags(string slug);
    }
}
